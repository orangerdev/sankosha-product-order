<?php
$user = wp_get_current_user();
?>
<form class="sankosha sankosha-order-form" action="" method="post">
    <h4>Product / Pump Request</h4>
    <div class="field required">
        <label for="name">
            <span>Name <sup>*</sup> </span>
            <input type="text" name="name" value="<?php echo $user->display_name; ?>" required />
        </label>
    </div>
    <div class="field">
        <label for="company">
            <span>Company </span>
            <input type="text" name="company" value="" />
        </label>
    </div>
    <div class="field">
        <label for="job-title">
            <span>Job Title </span>
            <input type="text" name="job-title" value="" />
        </label>
    </div>
    <div class="field required">
        <label for="email">
            <span>Email <sup>*</sup> </span>
            <input type="email" name="email" value="<?php echo $user->user_email; ?>" required />
        </label>
    </div>
    <div class="field">
        <label for="phone">
            <span>Phone </span>
            <input type="tel" name="phone" value="" placeholder="021-" />
        </label>
    </div>
    <div class="field">
        <label for="mobile">
            <span>Mobile</span>
            <input type="tel" name="mobile" value="" />
        </label>
    </div>
    <div class="field">
        <label for="address">
            <span>Address</span>
            <textarea name="address" rows="8" cols="80"></textarea>
        </label>
    </div>
    <div class="field">
        <label for="product">
            <span>Product</span>
            <input type="text" disabled value="<?php the_title(); ?>">
        </label>
    </div>
    <div class="field">
        <label for="order">
            <span>Total Order</span>
            <input type="number" class='sankosha-total-order' name="order" value="1" min="1" />
        </label>
    </div>
    <div class="message info">
        <p>Lead time</p>
    </div>
    <div class="message error">
        <p>Error</p>
    </div>
    <div class="message success">
        <p>Success</p>
    </div>
    <button type="submit" name="button">Order</button>
</form>
