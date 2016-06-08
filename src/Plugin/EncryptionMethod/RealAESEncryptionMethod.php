<?php

/**
 * @file
 * Contains \Drupal\real_aes\Plugin\EncryptionMethod\RealAESEncryptionMethod.
 */

namespace Drupal\real_aes\Plugin\EncryptionMethod;

use Drupal\encrypt\EncryptionMethodInterface;
use Drupal\encrypt\Plugin\EncryptionMethod\EncryptionMethodBase;
use Drupal\real_aes\Legacy\Defuse\Crypto;
use Drupal\real_aes\Legacy\Defuse\Exception as Ex;
use Drupal\real_aes\Legacy\Defuse\ExceptionHandler as ExceptionHandler;

/**
 * Class RealAESEncryptionMethod.
 *
 * @EncryptionMethod(
 *   id = "real_aes",
 *   title = @Translation("Authenticated AES (Real AES Legacy)"),
 *   description = "Legacy Authenticated encryption based on AES-128 in CBC mode. Verifies ciphertext integrity via an Encrypt-then-MAC scheme using HMAC-SHA256.",
 *   key_type = {"aes_encryption"}
 * )
 */
class RealAESEncryptionMethod extends EncryptionMethodBase implements EncryptionMethodInterface {

  /**
   * {@inheritdoc}
   */
  public function checkDependencies($text = NULL, $key = NULL) {
    $errors = array();

    // Check if we have a 128 bit key.
    if (strlen($key) != 16) {
      $errors[] = t('This encryption method requires a 128 bit key.');
    }

    return $errors;
  }

  /**
   * {@inheritdoc}
   */
  public function encrypt($text, $key, $options = array()) {

    try {
      return Crypto::encrypt($text, $key);
    }
    catch (Ex\CryptoTestFailedException $ex) {
      return FALSE;
    } catch (Ex\CannotPerformOperationException $ex) {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function decrypt($text, $key, $options = array()) {

    try {
      return Crypto::decrypt($text, $key);
    }
    catch (Ex\CryptoTestFailedException $ex) {
      return FALSE;
    }
    catch (Ex\CannotPerformOperationException $ex) {
      return FALSE;
    }
  }

}
