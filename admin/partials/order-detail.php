<?php
    $product = get_post($post->post_parent);
    $data    = get_post_meta($post->ID,'order_data',true);
?>
<table class='form-table'>
    <tbody>
        <tr>
            <th>Name</th>
            <td><?php echo $data['name']; ?></td>
        </tr>
        <tr>
            <th>Company</th>
            <td><?php echo $data['company']; ?></td>
        </tr>
        <tr>
            <th>Job Title</th>
            <td><?php echo $data['job-title']; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $data['email']; ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo $data['phone']; ?></td>
        </tr>
        <tr>
            <th>Mobile</th>
            <td><?php echo $data['mobile']; ?></td>
        </tr>
        <tr>
            <th>Address</th>
            <td><?php echo $data['Address']; ?></td>
        </tr>
        <tr>
            <th>Product</th>
            <td><?php echo $product->post_title; ?></td>
        </tr>
        <tr>
            <th>Total Order</th>
            <td><?php echo $data['order']; ?></td>
        </tr>
    </tbody>
</table>
