<div id="add-stock-modal" style="display:none;">
    <form id="add-stock-form" class="stock-form" action="">
        <div class="form-row">
            <label>Stock Ok</label>
            <input type="number" name="stock_ok" value="1" min="1">
        </div>
        <div class="form-row">
            <label>Stock Unschedule</label>
            <input type="number" name="stock_unschedule" value="1" min="1">
        </div>
        <div class="form-row">
            <label>Comment</label>
            <textarea name="comment">Manual Add</textarea>
        </div>
        <input type="hidden" class="product_id" name="product_id" value="">
        <?php wp_nonce_field('add-stock-nonce', 'add_stock_submit'); ?>
        <input type="submit" class="button button-primary" value="Submit">
        <div id="add-stock-alert"></div>
    </form>
</div>
<div id="reduce-stock-modal" style="display:none;">
    <form id="reduce-stock-form" class="stock-form" action="">
        <div class="form-row">
            <label>Stock Ok</label>
            <input type="number" name="stock_ok" value="1" min="1">
        </div>
        <div class="form-row">
            <label>Stock Unschedule</label>
            <input type="number" name="stock_unschedule" value="1" min="1">
        </div>
        <div class="form-row">
            <label>Comment</label>
            <textarea name="comment">Manual Reduce</textarea>
        </div>
        <input type="hidden" class="product_id" name="product_id" value="">
        <?php wp_nonce_field('reduce-stock-nonce', 'reduce_stock_submit'); ?>
        <input type="submit" class="button button-primary" value="Submit">
        <div id="reduce-stock-alert"></div>
    </form>
</div>
<script id="alert-template" type="text/x-jsrender">
<div class="alert alert-{{:type}}">
    <h2>{{:type}}</h2>
    {{if messages}}
        <ul>
        {{props messages}}
            <li>{{>prop}}</li>
        {{/props}}
        </ul>
    {{/if}}
</div>
</script>