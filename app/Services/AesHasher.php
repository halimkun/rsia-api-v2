<?php

namespace App\Services;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class AesHasher implements HasherContract
{
  /**
   * Get information about the given hashed value.
   *
   * @param  string  $hashedValue
   * @return array
   */
  public function info($hashedValue)
  {
    return [];
  }

  /**
   * Hash the given value.
   *
   * @param  string  $value
   * @return string
   */
  public function make($value, array $options = [])
  {
    // Gunakan AES_ENCRYPT untuk mengenkripsi nilai
    return 'AES_ENCRYPT("' . $value . '", "' . $options['key'] . '")';
  }

  /**
   * Check the given plain value against a hash.
   *
   * @param  string  $value
   * @param  string  $hashedValue
   * @param  array  $options
   * @return bool
   */
  public function check($value, $hashedValue, array $options = [])
  {
    // Gunakan AES_DECRYPT untuk mendekripsi nilai
    return 'AES_DECRYPT("' . $hashedValue . '", "' . $options['key'] . '")' === $value;
  }

  /**
   * Check if the given hash has been hashed using the given options.
   *
   * @param  string  $hashedValue
   * @param  array  $options
   * @return bool
   */
  public function needsRehash($hashedValue, array $options = [])
  {
    return false;
  }

  /**
   * Encrypt the given value.
   * 
   * @param string $value
   * @param array $options
   * 
   * @return string
   * */ 
  public function decrypt($column, array $options = [])
  {
    return 'AES_DECRYPT(`' . $column . '`, "' . $options['key'] . '")';
  }
}
