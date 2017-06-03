<?php

namespace Framework\Util\Verify;

/**
 * 验证码生成类
 *
 * @author Lee
 */
class VerifyCode {
	const FONT_DIR = 'Framework/Util/Verify/';
	private $code;
	private $codeSize = 4;
	private $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	private $width = 100;
	private $height = 30;
	private $font = '';
	private $fontSize = 20;
	private $image;
	
	/**
	 * 构造函数
	 *
	 * @param integer $width
	 *        	验证码图像宽度
	 * @param integer $height
	 *        	验证码图像高度
	 * @param integer $codeSize
	 *        	验证码字符数
	 * @param string $font
	 *        	字体文件
	 * @param integer $fontSize
	 *        	字体大小
	 */
	public function __construct($width = 100, $height = 30, $codeSize = 4, $font = 'ttfs/6.ttf', $fontSize = 20) {
		$this->width = $width;
		$this->height = $height;
		$this->codeSize = $codeSize;
		$this->font = self::FONT_DIR . $font;
		$this->fontSize = $fontSize;
	}
	
	/**
	 * 生成验证码图像
	 */
	public function getVerifyCodeImage() {
		$this->createVerifyCode ();
		$this->drawBackground ();
		$this->drawCode ();
		$this->drawDisturbLine ();

		header ( "Content-Type:image/png" );
		imagepng ( $this->image );
		imagedestroy ( $this->image );
	}
	
	/**
	 * 验证用户输入的验证码是否正确
	 * 
	 * @return true表示验证通过
	 */
	public function check($vcode) {
		return strtolower($_SESSION['vcode']) == strtolower($vcode);
	}
	
	/**
	 * 绘制背景
	 */
	private function drawBackground() {
		$this->image = imagecreatetruecolor ( $this->width, $this->height );
		$bgColor = imagecolorallocate ( $this->image, mt_rand ( 157, 255 ), mt_rand ( 157, 255 ), mt_rand ( 157, 255 ) );
		imagefilledrectangle ( $this->image, 0, 0, $this->width, $this->height, $bgColor );
	}
	
	/**
	 * 绘制验证码
	 */
	private function drawCode() {
		$w = $this->width / strlen ( $this->code );
		
		for($i = 0; $i < strlen ( $this->code ); $i ++) {
			$color = imagecolorallocate ( $this->image, mt_rand ( 0, 156 ), mt_rand ( 0, 156 ), mt_rand ( 0, 156 ) );
			imagettftext ( $this->image, $this->fontSize, mt_rand ( - 30, 30 ), $i * $w + mt_rand ( 1, 5 ), $this->height / 1.3, $color, $this->font, $this->code [$i] );
		}
	}
	
	/**
	 * 绘制干扰内容
	 */
	private function drawDisturbLine() {
		for($i = 0; $i < 5; $i ++) {
			$color = imagecolorallocate ( $this->image, mt_rand ( 0, 156 ), mt_rand ( 0, 156 ), mt_rand ( 0, 156 ) );
			imageline ( $this->image, mt_rand ( 0, $this->width ), mt_rand ( 0, $this->height ), mt_rand ( 0, $this->width ), mt_rand ( 0, $this->height ), $color );
		}
		for($i = 0; $i < 10; $i ++) {
			imagestring ( $this->image, 1, mt_rand ( 0, $this->width ), mt_rand ( 0, $this->height ), "*", 0xffffff );
		}
	}
	
	/**
	 * 生成验证码字符串，并存入session
	 */
	private function createVerifyCode() {
		$code = '';
		for($i = 0; $i < $this->codeSize; $i ++) {
			$index = mt_rand ( 0, strlen ( $this->charset ) - 1 );
			$code .= $this->charset [$index];
		}
		
		if (session_status () != PHP_SESSION_ACTIVE) {
			session_start ();
		}
		$this->code = $code;
		$_SESSION ['vcode'] = $code;
	}
}