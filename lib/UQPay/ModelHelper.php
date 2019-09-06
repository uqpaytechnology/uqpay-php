<?php


namespace uqpay\payment;

use ReflectionException;
use ReflectionClass;
use uqpay\payment\config\security\SecurityConfig;
use uqpay\payment\model\PaymentParameter;
use uqpay\payment\model\ResultParameter;

class ModelHelper {

	/**
	 * @param PaymentParameter[] $parameters
	 *
	 * @return array
	 */
	public static function assemblyOrderData( PaymentParameter ...$parameters ) {
		$result = array();
		foreach ($parameters as $parameter) {
			$result = array_merge($result, $parameter->getRequestArr());
		}
		return $result;
	}

	/**
	 * @param array $result_array
	 * @param string $result_class_name
	 *
	 * @return object
	 * @throws ReflectionException
	 */
	public static function parseResultData( array &$result_array, $result_class_name ) {
		$result_class = new ReflectionClass( $result_class_name );
		$result       = $result_class->newInstance();
		$result->parseResultData( $result_array );

		return $result;
	}

	/**
	 * @param array $params_array
	 * @param SecurityConfig $security_config
	 *
	 * @throws config\security\SecurityUqpayException
	 */
	public static function signRequestParams( array &$params_array, SecurityConfig $security_config ) {
		ksort( $params_array );
		$sign_target                               = urldecode( http_build_query( $params_array ) );
		$sign_result                               = $security_config->sign( $sign_target );
		$params_array[ Constants::AUTH_SIGN ]      = $sign_result['signature'];
		$params_array[ Constants::AUTH_SIGN_TYPE ] = $sign_result['signature_type'];
	}

	/**
	 * @param array $origin_array
	 * @param SecurityConfig $security_config
	 *
	 * @return bool
	 * @throws UqpayException
	 * @throws config\security\SecurityUqpayException
	 */
	public static function verifyPaymentResult( array $origin_array, SecurityConfig $security_config ) {
		if ( ! isset( $origin_array[ Constants::AUTH_SIGN ] ) ) {
			return false;
		}
		$signature = $origin_array[ Constants::AUTH_SIGN ];
		unset( $origin_array[ Constants::AUTH_SIGN ] );
		unset( $origin_array[ Constants::AUTH_SIGN_TYPE ] );
		ksort( $origin_array );
		$verify_target = urldecode( http_build_query( $origin_array ) );
		var_dump($verify_target);
		return $security_config->verify( $verify_target, $signature );
	}
}