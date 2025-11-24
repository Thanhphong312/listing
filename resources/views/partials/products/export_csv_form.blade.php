<form method="POST" id="products_export_form" action="{{ route('products.export') }}" class="export-products-trigger d-none">
  @csrf
  <input name="product_ids" value="" type="hidden" />
  <input name="type" value="TIKTOK" type="hidden" />
  <h3>Export selected products</h3>
  <!-- Radio Buttons for CSV and Batch -->
  <div class="form-group">
    <label>Warehouse ID</label>
    <input type="text" name="warehouse_id" required true />
  </div>
  <div class="form-group">
    <label>Select categories</label>
    <select class="form-control" type="select" id="category" name="category" required>
      <option value="">-- Select --</option>
      <option value="Women's Tops/Hoodies & Sweaters (601295)">Women's Tops/Hoodies & Sweaters
        (601295)</option>
      <option value="Women's Tops/T-shirts (601302)">Women's Tops/T-shirts (601302)</option>
      <option value="Women's Underwear/Panties (601247)">Women's Underwear/Panties (601247)
      </option>
      <option value="Women's Underwear/Shapewear (601259)">Women's Underwear/Shapewear
        (601259)
      </option>
      <option value="Women's Underwear/Bras (601262)">Women's Underwear/Bras (601262)</option>
      <option value="Women's Bottoms/Skirts (601264)">Women's Bottoms/Skirts (601264)</option>
      <option value="Women's Tops/Blouses & Shirts (601265)">Women's Tops/Blouses & Shirts
        (601265)</option>
      <option value="Women's Bottoms/Shorts (601266)">Women's Bottoms/Shorts (601266)</option>
      <option value="Women's Tops/Jackets & Coats (601267)">Women's Tops/Jackets & Coats
        (601267)
      </option>
      <option value="Women's Dresses/Wedding Dresses (601270)">Women's Dresses/Wedding Dresses
        (601270)</option>
      <option value="Women's Dresses/Formal Dresses (601271)">Women's Dresses/Formal Dresses
        (601271)</option>
      <option value="Women's Bottoms/Leggings (601274)">Women's Bottoms/Leggings (601274)
      </option>
      <option value="Women's Bottoms/Jeans (601276)">Women's Bottoms/Jeans (601276)</option>
      <option value="Women's Bottoms/Trousers (601277)">Women's Bottoms/Trousers (601277)
      </option>
      <option value="Women's Suits & Overalls/Overalls (601280)">Women's Suits &
        Overalls/Overalls
        (601280)</option>
      <option value="Women's Dresses/Casual Dresses (601281)">Women's Dresses/Casual Dresses
        (601281)</option>
      <option value="Women's Tops/Vests (601282)">Women's Tops/Vests (601282)</option>
      <option value="Women's Tops/Knitwear (601284)">Women's Tops/Knitwear (601284)</option>
      <option value="Women's Suits & Overalls/Sets (601291)">Women's Suits & Overalls/Sets
        (601291)</option>
      <option value="Women's Suits & Overalls/Suits (601296)">Women's Suits & Overalls/Suits
        (601296)</option>
      <option value="Women's Sleepwear & Loungewear/Onesies (803216)">Women's Sleepwear &
        Loungewear/Onesies (803216)</option>
      <option value="Women's Tops/Vest, Tank & Tube Tops (843400)">Women's Tops/Vest, Tank &
        Tube
        Tops (843400)</option>
      <option value="Women's Tops/Bodysuits (843528)">Women's Tops/Bodysuits (843528)</option>
      <option value="Women's Special Clothing/Costumes & Accessories (843656)">Women's Special
        Clothing/Costumes & Accessories (843656)</option>
      <option value="Women's Special Clothing/Workwear & Uniforms (843784)">Women's Special
        Clothing/Workwear & Uniforms (843784)</option>
      <option value="Women's Special Clothing/Traditional Dress (843912)">Women's Special
        Clothing/Traditional Dress (843912)</option>
      <option value="Women's Suits & Overalls/Couples' Clothing Sets (844040)">Women's Suits &
        Overalls/Couples' Clothing Sets (844040)</option>
      <option value="Women's Suits & Overalls/Family Clothing Sets (844296)">Women's Suits &
        Overalls/Family Clothing Sets (844296)</option>
      <option value="Women's Underwear/Tights (844552)">Women's Underwear/Tights (844552)
      </option>
      <option value="Women's Underwear/Thermal Underwear (844680)">Women's Underwear/Thermal
        Underwear (844680)</option>
      <option value="Women's Underwear/Bra Accessories (844936)">Women's Underwear/Bra
        Accessories
        (844936)</option>
      <option value="Women's Underwear/Lingerie (845192)">Women's Underwear/Lingerie (845192)
      </option>
      <option value="Women's Underwear/Socks (845448)">Women's Underwear/Socks (845448)
      </option>
      <option value="Women's Underwear/Bralettes (845576)">Women's Underwear/Bralettes
        (845576)
      </option>
      <option value="Women's Underwear/Underwear Sets (845704)">Women's Underwear/Underwear
        Sets
        (845704)</option>
      <option value="Women's Sleepwear & Loungewear/Pajamas (845832)">Women's Sleepwear &
        Loungewear/Pajamas (845832)</option>
      <option value="Women's Sleepwear & Loungewear/Bathrobes & Dressing Gowns (845960)">
        Women's
        Sleepwear & Loungewear/Bathrobes & Dressing Gowns (845960)</option>
      <option value="Women's Sleepwear & Loungewear/Nightdresses (846088)">Women's Sleepwear &
        Loungewear/Nightdresses (846088)</option>
      <option value="Women's Tops/Polo Shirts (961032)">Women's Tops/Polo Shirts (961032)
      </option>
      <option value="Women's Dresses/Bridesmaid Dresses (961672)">Women's Dresses/Bridesmaid
        Dresses (961672)</option>
    </select>
  </div>

  <div class="form-group">
    <label>Size chart</label>
    <input class="form-control" name="size_chart" />
  </div>

  <div class="form-group">
    <label>Description</label>
    <textarea class="form-control" name="description"></textarea>
  </div>

  <!-- Submit Button -->
  <button type="submit" class="btn btn-primary">Download Tiktok CSV</button>
</form>