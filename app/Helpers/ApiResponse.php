<?php

	namespace App\Helpers;
	use Symfony\Component\HttpFoundation\Response as FoundationResponse;
	use App\Helpers\Code as CodeStatusTexts;
	use Response;

	trait ApiResponse
	{
		/**
		 * @var int
		 */
		protected $statusCode = 200;

		/**
		 * @return mixed
		 */
		public function getStatusCode()
		{
			return $this->statusCode;
		}

		/**
		 * @param $statusCode
		 * @return $this
		 */
		public function setStatusCode($statusCode)
		{
			$this->statusCode = $statusCode;
			return $this;
		}

		/**
		 * @param $data
		 * @param array $header
		 * @return mixed
		 */
		public function respond($data, $header = [])
		{

			return Response::json($data,$this->getStatusCode(),$header);
		}

		/**
		 * @param $status
		 * @param array $data
		 * @param null $code
		 * @return mixed
		 */
		public function status(array $data, $code = null)
		{

			if ($code){
				$this->setStatusCode($code);
			}
			$code = $this->getStatusCode();
			$statusCode = substr($code,0,3);
			$status = [];
			if($statusCode < 200 || $statusCode >299){
				$status = [
					'error_code' => $code
				];
			}
			$this->setStatusCode($statusCode);
			
			$data = array_merge($status,$data);
			return $this->respond($data);

		}

		/**
		 * @param $message
		 * @param int $code
		 * @param string $status
		 * @return mixed
		 */
		public function failed($code = FoundationResponse::HTTP_BAD_REQUEST,$message='')
		{
			$msg = CodeStatusTexts::getStatusText($code);
			return $this->setStatusCode($code)->message($msg?$msg:$message);
		}

		/**
		 * @param $message
		 * @param array $message
		 * @return mixed
		 */
		public function message($message)
		{
			return $this->status(['message' => $message]);
		}


		/**
		 * @param string $message
		 * @return mixed
		 */
		public function internalError($message = "Internal Error!")
		{

			return $this->failed($message,FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
		}

		/**
		 * created success
		 * @return mixed
		 */
		public function created($data = [])
		{
			$this->setStatusCode(201);
			return $this->respond($data);
		}

		/**
		 * accepted success
		 * @return mixed
		 */
		public function accepted($data = [])
		{
			$this->setStatusCode(202);
			return $this->respond($data);
		}

		/**
		 * delete or put or patch success
		 * @return mixed
		 */
		public function deleteOrPutPatch($data = [])
		{
			$this->setStatusCode(204);
			return $this->respond($data);
		}


		/**
		 * @param $data
		 * @param string $status
		 * @return mixed
		 */
		public function success($data)
		{
			//$data = compact('data');
			return $this->status($data);
		}

		/**
		 * @param string $message
		 * @return mixed
		 */
		public function notFond($message = 'Not Fond!')
		{
			return $this->failed(Foundationresponse::HTTP_NOT_FOUND,$message);
		}

	}