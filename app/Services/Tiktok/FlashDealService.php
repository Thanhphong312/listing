<?php

namespace App\Services\Tiktok;


use App\Repositories\Flashdeal\FlashDealRepository;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\Tiktok\ConnectAppPartnerService;
use App\Models\FlashDeal;
use App\Models\Notification;
use App\Services\Notification\NotificationService;
use App\Models\Store\Store;
use App\Jobs\FlashdealProductAllJobs;

class FlashDealService
{
  // public function __construct(
  //   protected FlashDealRepository $flashDealRepository,
  //   protected ConnectAppPartnerService $ConnectAppPartnerService
  // ) {
  // }
  protected $flashDealRepository;
  protected $connectAppPartnerService;
  protected $notificationService;

  public function __construct(
    FlashDealRepository $flashDealRepository,
    ConnectAppPartnerService $connectAppPartnerService,
    NotificationService $notificationService
  ) {
    $this->flashDealRepository = $flashDealRepository;
    $this->connectAppPartnerService = $connectAppPartnerService;
    $this->notificationService = $notificationService;
  }

  public function getKeys(array $filter = [], $orderBy = null, $perPage, $status = null)
  {
    return $this->flashDealRepository->paginate($filter, $orderBy, $perPage, $status);
  }

  public function createKeys(array $data)
  {
    return $this->flashDealRepository->create($data);
  }

  public function updateDealStatus($id, array $data)
  {
    $action = $data['action'];

    //info flashdeal old
    $flashDealSup = FlashDeal::findOrFail($id);
    $storeSup = $flashDealSup->store;
    $domainSup = $storeSup->domain;
    $usernameSup = $storeSup->user->username;
    $dealNameSup = $flashDealSup->deal_name;
    $dealIdSup = $flashDealSup->deal_id;

    $infoStoreSup = 'ACTION FLASHDEAL - ' . $domainSup . ' - ' . $usernameSup . ' - ' . $dealNameSup;

    try {
      //connect tiktok app partner
      $clientAppPartner = $this->connectAppPartnerService->connectAppPartner($storeSup);
      if (isset($clientAppPartner['client'])) {
        $clientAppPartner = $clientAppPartner['client'];
        //get flashdeal tiktok
        $flashdealTiktok = $clientAppPartner->Promotion->getActivity($dealIdSup);
        // return $flashdealTiktok;
        if (isset($flashdealTiktok['activity_id'])) {

          $dealNameTiktok = $flashdealTiktok['title'];
          $dealIdTiktok = $flashdealTiktok['activity_id'];
          $dealStatusTiktok = $flashdealTiktok['status'];
          $dealProductTiktok = $flashdealTiktok['products'];

          //deactive flashdeal if action inactive
          if ($action == 'inactive') {

            $flashDealSup->action = $action;
            $flashDealSup->deal_status = $dealStatusTiktok;

            if ($dealStatusTiktok == 'ONGOING') {
              $deactivateActivity = $clientAppPartner->Promotion->deactivateActivity($dealIdSup);

              if ($deactivateActivity['status'] == 'DEACTIVATED') {
                $flashDealSup->deal_status = 'DEACTIVATED';
                Log::channel('flashdeal-cron')->info($infoStoreSup . ' - DEACTIVATED flashdeal success');
              }
            }
            $flashDealSup->save();
            return $flashDealSup;
          }

          if ($action == 'active') {

            // $title_old = 'donal deal supdeal 30%';
            $title_old = $dealNameTiktok;
            //tach auto ra khoi title
            $checkTextAuto = '/(.+)\s*auto\s*(\d+)/';
            if (preg_match($checkTextAuto, $title_old, $matches)) {
              $title_old_first = trim($matches[1]);
            } else {
              $title_old_first = trim($title_old);
            }
            // return $title_old_first;
            // Log::channel('flashdeal-cron')->info($infoStoreSup . ' - title - ' . $title_old_first);
            // check xem co flashdeal nao cung title ma run ko
            $searchActivitiesOngoing = $clientAppPartner->Promotion->searchActivities([
              'activity_type' => 'FLASHSALE',
              'status' => 'ONGOING',
              'activity_title' => $title_old_first . ' auto'
            ]);
            if (count($searchActivitiesOngoing['activities']) >= 1) {
              $flashDealSup->action = $dealStatusTiktok == 'ONGOING' ? 'active' : 'inactive';
              $flashDealSup->deal_status = $dealStatusTiktok;
              $flashDealSup->save();
              Log::channel('flashdeal-cron')->info($infoStoreSup . ' - flashdeal dang run, ko the dup - ' . json_encode($searchActivitiesOngoing['activities']));
              return 'flashdeal old dang run';
            }
            // return $searchActivitiesOngoing;

            // search xem co trung ko
            $searchActivities = $clientAppPartner->Promotion->searchActivities([
              'activity_type' => 'FLASHSALE',
              'page_size' => 100,
              'activity_title' => $title_old_first
            ]);

            // return $title_old_first;
            // check xem co title trung ko
            $arrNumber = [];
            $flashdealTiktokFirst = [];
            foreach ($searchActivities['activities'] as $searchActivitie) {
              if ((stripos($searchActivitie['title'], $title_old_first)) !== false) {
                preg_match($checkTextAuto, $searchActivitie['title'], $matches);
                $arrNumber[] = $matches[2] ?? '0';
              }
              if (trim($searchActivitie['title']) == $title_old_first) {
                $flashdealTiktokFirst = $clientAppPartner->Promotion->getActivity($searchActivitie['id']);
              }
            }

            $max_number = max($arrNumber);
            $title_new = trim($title_old_first) . ' auto ' . ++$max_number;
            // return $title_new;
            $title = $title_new;
            $type = 'FLASHSALE';
            $begin_time = Carbon::now()->timestamp;

            $randomHour = rand(0, 2); // Giờ từ 0 đến 3
            $randomMinute = rand(0, 59); // Phút từ 0 đến 59
            $end_time = Carbon::now()->addDays(3)->startOfDay();
            $end_time = $end_time->addHours($randomHour)->addMinutes($randomMinute)->timestamp;
            // $end_time = Carbon::now()->addMinutes(60)->timestamp;
            $product_level = 'VARIATION';

            if (!isset($flashdealTiktokFirst['products']) || empty($flashdealTiktokFirst['products']) || strlen($title) > 40) {
              if (strlen($title) > 40) {
                Log::channel('flashdeal-cron')->info($infoStoreSup . ' - Title: can not be blank and max length is 40 - ' . strlen($title));
              } else {
                Log::channel('flashdeal-cron')->info($infoStoreSup . ' - flashdeal first tiktok have product exist');
              }
              $flashDealSup->action = $dealStatusTiktok == 'ONGOING' ? 'active' : 'inactive';
              $flashDealSup->deal_status = $dealStatusTiktok;
              $flashDealSup->save();
              return 'flashdeal product exist or Title: can not be blank and max length is 40';
            }

            //convert product flashdeal old -> new
            if(isset($flashdealTiktokFirst['products'])){
              $product_olds = $flashdealTiktokFirst['products'];

              $product_news = [];

              $checkNumVariant = 0;
              foreach ($product_olds as $product_old) {
                $product_old_id = $product_old['id'];

                $sku_olds = [];
                if (isset($product_old['skus'])) {
                  foreach ($product_old['skus'] as $sku_old) {
                    $sku_convert = [
                      "activity_price_amount" => $sku_old['activity_price']['amount'],
                      "id" => $sku_old['id'],
                      "quantity_limit" => -1,
                      "quantity_per_user" => -1,
                    ];
                    $sku_olds[] = $sku_convert;
                  }
                }


                $product_convert = [
                  "activity_price" => [
                    "currency" => "USD"
                  ],
                  "id" => $product_old_id,
                  "quantity_limit" => -1,
                  "quantity_per_user" => -1,
                  "skus" => $sku_olds

                ];
                // $product_news[] = $product_convert;

                //update product duoi 3000
                $checkNumVariant += count($sku_olds);
                if ($checkNumVariant <= 300) {
                  $product_news[] = $product_convert;
                } else {
                  break;
                }
              }
              // return count($product_news);
              // return $checkNumVariant;
              // return $product_news;

              $createActivity = $clientAppPartner->Promotion->createActivity($title, $type, $begin_time, $end_time, $product_level);
            }

            if ($createActivity['status'] == 'ONGOING') {

              $flashDealSup->deal_name = $title;
              $flashDealSup->start = date('Y-m-d H:i:s', $begin_time);
              $flashDealSup->expire = date('Y-m-d H:i:s', $end_time);
              $flashDealSup->action = 'active';
              $flashDealSup->deal_id = $createActivity['activity_id'];
              $flashDealSup->deal_status = $createActivity['status'];
              if (count($product_news) !== count($product_olds)) {
                $flashDealSup->deal_status = 'PRODUCT UPDATE';
              }
              $flashDealSup->save();
              // Log::channel('flashdeal-cron')->info($infoStoreSup . ' - Flashdeal updated success '. json_encode($createActivity));

              try {
                $updateActivityProduct = $clientAppPartner->Promotion->updateActivityProduct($createActivity['activity_id'], $product_news);
                if (!isset($updateActivityProduct['activity_id'])) {
                  $flashDealSup->deal_status = 'PRODUCT UPDATE';
                  $flashDealSup->save();
                }
                Log::channel('flashdeal-cron')->info($infoStoreSup . ' - Flashdeal add product success ' . json_encode($updateActivityProduct));
              } catch (\Exception $e) {
                Log::channel('flashdeal-cron')->error($infoStoreSup . ' - flashdeal add product error - ' . $e->getMessage());
              }
              return $flashDealSup;
            }
            // else {
            //   $flashDealSup->deal_status = 'WAITING DUPLICATE';
            //   $flashDealSup->save();
            // }
          }
        } else {
          Log::channel('flashdeal-cron')->error($infoStoreSup . ' - Flashdeal not found');
          return 'Flashdeal tiktok not found';
        }
      } else {
        Log::channel('flashdeal-cron')->error($infoStoreSup . ' - Connect App Partner failed');
        return 'Connect App Partner failed. check proxy, or auth app';
      }
    } catch (\Exception $e) {
      Log::channel('flashdeal-cron')->error($infoStoreSup . ' - flashdeal error - ' . $e->getMessage());

      $data = [
        'msg' => $infoStoreSup . 'flashdeal error - ' . $e->getMessage(),
        'object_id' => $storeSup->id,
        'object' => 'flashdeal',
        'isSeen' => 0,
        'user_id' => $storeSup->user_id,
      ];
      $createNotifi = $this->notificationService->createNotification($data);

      return $e->getMessage();
    }
  }
  // public function updateDealStatusOld($id, array $data)
  // {
  //   $action = $data['action'];

  //   //info flashdeal old
  //   $flashDealSup = FlashDeal::findOrFail($id);
  //   $storeSup = $flashDealSup->store;
  //   $domainSup = $storeSup->domain;
  //   $usernameSup = $storeSup->user->username;
  //   $dealNameSup = $flashDealSup->deal_name;
  //   $dealIdSup = $flashDealSup->deal_id;

  //   $infoStoreSup = 'ACTION FLASHDEAL - ' . $domainSup . ' - ' . $usernameSup . ' - ' . $dealNameSup;

  //   try {
  //     //connect tiktok app partner
  //     $clientAppPartner = $this->connectAppPartnerService->connectAppPartner($storeSup);
  //     if (isset($clientAppPartner['client'])) {
  //       $clientAppPartner = $clientAppPartner['client'];
  //       //get flashdeal tiktok
  //       $flashdealTiktok = $clientAppPartner->Promotion->getActivity($dealIdSup);
  //       // return $flashdealTiktok;
  //       if (isset($flashdealTiktok['activity_id'])) {

  //         $dealNameTiktok = $flashdealTiktok['title'];
  //         $dealIdTiktok = $flashdealTiktok['activity_id'];
  //         $dealStatusTiktok = $flashdealTiktok['status'];
  //         $dealProductTiktok = $flashdealTiktok['products'];

  //         //deactive flashdeal if action inactive
  //         if ($action == 'inactive') {

  //           $flashDealSup->action = $action;
  //           $flashDealSup->deal_status = $dealStatusTiktok;

  //           if ($dealStatusTiktok == 'ONGOING') {
  //             $deactivateActivity = $clientAppPartner->Promotion->deactivateActivity($dealIdSup);

  //             if ($deactivateActivity['status'] == 'DEACTIVATED') {
  //               $flashDealSup->deal_status = 'DEACTIVATED';
  //               Log::channel('flashdeal-cron')->info($infoStoreSup . ' - DEACTIVATED flashdeal success');
  //             }
  //           }
  //           $flashDealSup->save();
  //           return $flashDealSup;
  //         }

  //         if ($action == 'active') {

  //           $title_old = $dealNameTiktok;
  //           // $title_old = 'donal deal supdeal 30%';

  //           //tach auto ra khoi title
  //           $checkTextAuto = '/(.+)\s*auto\s*(\d+)/';
  //           if (preg_match($checkTextAuto, $title_old, $matches)) {
  //             $title_old_first = trim($matches[1]);
  //           } else {
  //             $title_old_first = trim($title_old);
  //           }

  //           // check xem co flashdeal nao cung title ma run ko
  //           $searchActivitiesOngoing = $clientAppPartner->Promotion->searchActivities([
  //             'activity_type' => 'FLASHSALE',
  //             'status' => 'ONGOING',
  //             'activity_title' => $title_old_first . ' auto'
  //           ]);
  //           // return $searchActivitiesOngoing;
  //           if (count($searchActivitiesOngoing['activities']) >= 1) {
  //             $flashDealSup->action = $dealStatusTiktok == 'ONGOING' ? 'active' : 'inactive';
  //             $flashDealSup->deal_status = $dealStatusTiktok;
  //             $flashDealSup->save();
  //             Log::channel('flashdeal-cron')->info($infoStoreSup . ' - flashdeal dang run, ko the dup - ' . json_encode($searchActivitiesOngoing['activities']));
  //             return 'flashdeal old dang run';
  //           }

  //           // search xem co trung ko
  //           $searchActivities = $clientAppPartner->Promotion->searchActivities([
  //             'activity_type' => 'FLASHSALE',
  //             'page_size' => 100,
  //             'activity_title' => $title_old_first
  //           ]);

  //           // check xem co title trung ko
  //           $arrNumber = [];
  //           $flashdealTiktokFirst = [];
  //           foreach ($searchActivities['activities'] as $searchActivitie) {
  //             if ((stripos($searchActivitie['title'], $title_old_first)) !== false) {
  //               preg_match($checkTextAuto, $searchActivitie['title'], $matches);
  //               $arrNumber[] = $matches[2] ?? '0';
  //             }
  //             if (trim($searchActivitie['title']) == $title_old_first) {
  //               $flashdealTiktokFirst = $clientAppPartner->Promotion->getActivity($searchActivitie['id']);
  //             }
  //           }
  //           $max_number = max($arrNumber);
  //           $title_new = $title_old_first . ' auto ' . ++$max_number;

  //           $title = $title_new;
  //           $type = 'FLASHSALE';
  //           $begin_time = Carbon::now()->timestamp;
  //           // $end_time = Carbon::now()->addDays(3)->startOfDay();
  //           // $end_time = $end_time->addHours(0)->timestamp;

  //           $randomHour = rand(0, 2); // Giờ từ 0 đến 3
  //           $randomMinute = rand(0, 59); // Phút từ 0 đến 59

  //           $end_time = Carbon::now()->addDays(3)->startOfDay();
  //           $end_time = $end_time->addHours($randomHour)->addMinutes($randomMinute)->timestamp;
  //           $product_level = 'VARIATION';

  //           if (strlen($title) > 40) {
  //             $title = rand(11111, 99999).' Title max length 40';
  //           }
            

  //           if(isset($flashdealTiktokFirst['products'])){
  //             $createActivity = $clientAppPartner->Promotion->createActivity($title, $type, $begin_time, $end_time, $product_level);
  //             $product_olds = $flashdealTiktokFirst['products'];
  //             if ($createActivity['status'] == 'ONGOING') {
  //               foreach ($product_olds as $product_old) {
  //                 // return $product_old;
  //                 $product_old_id = $product_old['id'];

  //                 $sku_olds = [];
  //                 if (isset($product_old['skus'])) {
  //                   foreach ($product_old['skus'] as $sku_old) {
  //                     $sku_convert = [
  //                       "activity_price_amount" => $sku_old['activity_price']['amount'],
  //                       "id" => $sku_old['id'],
  //                       "quantity_limit" => -1,
  //                       "quantity_per_user" => -1,
  //                     ];
  //                     $sku_olds[] = $sku_convert;
  //                   }
  //                 }


  //                 $product_convert = [
  //                   "activity_price" => [
  //                     "currency" => "USD"
  //                   ],
  //                   "id" => $product_old_id,
  //                   "quantity_limit" => -1,
  //                   "quantity_per_user" => -1,
  //                   "skus" => $sku_olds

  //                 ];

  //                 $infoStoreSupNew = 'JOBS - '.$infoStoreSup.' - '.$product_old_id.' - ';
  //                 $createActivityId = $createActivity['activity_id'];
  //                 $productAdd = [$product_convert];
  //                 $jobs = FlashdealProductAllJobs::dispatch($product_old_id, $createActivityId, $productAdd, $infoStoreSupNew, $clientAppPartner)->onQueue('flashdeal_product_all');
            
  //               }

  //               $flashDealSup->deal_name = $title;
  //               $flashDealSup->start = date('Y-m-d H:i:s', $begin_time);
  //               $flashDealSup->expire = date('Y-m-d H:i:s', $end_time);
  //               $flashDealSup->action = 'active';
  //               $flashDealSup->deal_id = $createActivity['activity_id'];
  //               $flashDealSup->deal_status = $createActivity['status'];
  //               $flashDealSup->save();
  //               // Log::channel('flashdeal-cron')->info($infoStoreSup . ' - Flashdeal updated success '. json_encode($createActivity));

                
  //               return $flashDealSup;
  //             }
  //           }
            

  //         }
  //       } else {
  //         Log::channel('flashdeal-cron')->error($infoStoreSup . ' - Flashdeal not found');
  //         return 'Flashdeal tiktok not found';
  //       }
  //     } else {
  //       Log::channel('flashdeal-cron')->error($infoStoreSup . ' - Connect App Partner failed');
  //       return 'Connect App Partner failed. check proxy, or auth app';
  //     }
  //   } catch (\Exception $e) {
  //     Log::channel('flashdeal-cron')->error($infoStoreSup . ' - flashdeal error - ' . $e->getMessage());

  //     $data = [
  //       'msg' => $infoStoreSup . 'flashdeal error - ' . $e->getMessage(),
  //       'object_id' => $storeSup->id,
  //       'object' => 'flashdeal',
  //       'isSeen' => 0,
  //       'user_id' => $storeSup->user_id,
  //     ];
  //     $createNotifi = $this->notificationService->createNotification($data);

  //     return $e->getMessage();
  //   }
  // }
  public function flashdealProductAll(array $data)
  {
    try {
      $store_id = $data['store_id'];
      $title = $data['name'];
      $discount = $data['discount'];

      //info flashdeal old
      $storeSup = Store::find($store_id);
      $domainSup = $storeSup->domain;
      $usernameSup = $storeSup->user->username;

      $infoStoreSup = 'ACTION FLASHDEAL PRODUCT ALL - ' . $domainSup . ' - ' . $usernameSup . ' - ';
      //connect tiktok app partner
      $clientAppPartner = $this->connectAppPartnerService->connectAppPartner($storeSup);
      if (isset($clientAppPartner['client'])) {
        $clientAppPartner = $clientAppPartner['client'];
        //get flashdeal tiktok

        $page_token = '';
        $allProducts = [];
        do {
          $searchProducts = $clientAppPartner->Product->searchProducts(
            [
              'page_size' => 50,
              'page_token' => $page_token
            ],
            ['status' => 'ACTIVATE']
          );

          // Thêm sản phẩm tìm được vào mảng
          $allProducts = array_merge($allProducts, $searchProducts['products']);
          $page_token = $searchProducts['next_page_token'];
        } while ($searchProducts['total_count'] >= 50 && $page_token);

        $title = $title . ' supdeal ' . rand(1111, 9999);
        $type = 'FLASHSALE';
        $begin_time = Carbon::now()->timestamp;
        $randomHour = rand(0, 2); // Giờ từ 0 đến 3
        $randomMinute = rand(0, 59); // Phút từ 0 đến 59

        $end_time = Carbon::now()->addDays(3)->startOfDay();
        $end_time = $end_time->addHours($randomHour)->addMinutes($randomMinute)->timestamp;
        $product_level = 'VARIATION';

        $createActivity = $clientAppPartner->Promotion->createActivity($title, $type, $begin_time, $end_time, $product_level);

        $flashdealAdd = new FlashDeal();
        $flashdealAdd->deal_id = $createActivity['activity_id'] ?? '';
        $flashdealAdd->store_id = $store_id;
        $flashdealAdd->deal_name = $title;
        $flashdealAdd->start = date('Y-m-d H:i:s', $begin_time);
        $flashdealAdd->expire = date('Y-m-d H:i:s', $end_time);
        $flashdealAdd->deal_status = $createActivity['status'];
        $flashdealAdd->action = $createActivity['status'] == 'ONGOING' ? 'active' : 'inactive';
        $flashdealAdd->save();

        foreach ($allProducts as $productTiktok) {
          $productTiktokId = $productTiktok['id'];

          $sku_olds = [];
          if (isset($productTiktok['skus'])) {
            foreach ($productTiktok['skus'] as $sku_old) {
              
              $sku_convert = [
                "activity_price_amount" => (string)((int)$sku_old['price']['tax_exclusive_price'] * (1 - ($discount / 100))),
                "id" => $sku_old['id'],
                "quantity_limit" => -1,
                "quantity_per_user" => -1,
              ];
              $sku_olds[] = $sku_convert;
            }
          }

          $product_convert = [
            "activity_price" => [
              "currency" => "USD"
            ],
            "id" => $productTiktokId,
            "quantity_limit" => -1,
            "quantity_per_user" => -1,
            "skus" => $sku_olds

          ];
          // dd($product_convert);
          if ($createActivity['status'] == 'ONGOING') {
            $infoStoreSupNew = 'JOBS - '.$infoStoreSup.$productTiktokId.' - ';
            $createActivityId = $createActivity['activity_id'];
            $productAdd = [$product_convert];
            $jobs = FlashdealProductAllJobs::dispatch($productTiktokId, $createActivityId, $productAdd, $infoStoreSupNew, $clientAppPartner)->onQueue('flashdeal_product_all');
          }

        }

        
        return 'success';

        
      } else {
        Log::channel('flashdeal-cron')->error($infoStoreSup . ' - Connect App Partner failed');
        return 'Connect App Partner failed. check proxy, or auth app';
      }
    } catch (\Exception $e) {
      Log::channel('flashdeal-cron')->error($infoStoreSup . ' - flashdeal error - ' . $e->getMessage());
      return $e->getMessage();
    }
  }
}
