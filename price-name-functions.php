function add_funky_meta($cart_object)
{
    if (!WC()->session->__isset("reload_checkout")) {
        $discount = 25; // This is a custom discount amount that I reference later.
        foreach ($cart_object->cart_contents as $key => $value) {

            $origName = $value['data']->get_name(); //For each item, I'll temporarily store it's original name here.

            if ($value['variation']['Finish'] == 'None') { // Set your condidtion for the price change here.
                $orgPrice = floatval($value['data']->get_price()); // Getting the original price.
                $discPrice = $orgPrice - $discount; // Calculating the discount or adjustment amount.
                $value['data']->set_price($discPrice); // This is the line that does the work of setting the price before adding it to the cart.
            }

            if (strpos($origName, 'Test Text') !== false) { //An initial check to see if your product name qualifies for the change.
                if ($value['variation']['Sample'] == 'Yes') { // This is a secondary price adjustment.
                    $value['data']->set_price(5); // New price set here because the item was marked as a sample.
                    $origName = str_replace('Test Text', '', $origName); // This removes a part of the name. 
                    $newName = "$origName Sample"; //This appends a word to the name.
                    $value['data']->set_name($newName); // This sets the new name.
                } else {
                    $splitted = explode(" - ", $origName); // Dividing a name into array based on a hyphen.
                    $newName = $splitted[0]; // Using the first part of the name.
                    $value['data']->set_name($newName); // Setting that as a new name.
                }
            }
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'add_funky_meta', 99);
