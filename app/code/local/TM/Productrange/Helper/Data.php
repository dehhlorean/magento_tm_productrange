<?php
class TM_Productrange_Helper_Data extends Mage_Core_Helper_Abstract {

  /**
   * Validate form request input
   * No null values
   * No non-numeric values for $hi and $lo
   * No negative numeric values for $hi and $lo
   * $hi must be greater $lo
   * $hi cannot be greater than 5 times $lo
   */
  public function validateRequest($hi, $lo, $sortBy)
  {
    if ((empty($hi) || empty($lo) || empty($sortBy))
      || (!is_numeric($hi) && !is_numeric($lo))
      || (($hi <= 0) || ($lo <= 0))
      || ($hi <= $lo)
      || ($hi > (5 * $lo))) {
      return false;
    } else {
      return true;
    }
  }
}
