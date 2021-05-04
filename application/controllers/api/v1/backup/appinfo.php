public function getAboutMember_get()
	{

		try
		{
			$query = $this->db->query(" SELECT
											AboutMember

										FROM app_info
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->row();

					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);

				if($result)
				{
					$this->response($data, 200);
				}
				else
				{
					$this->response($data, 404);
				}
		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}
	}

	public function getFAQ_get()
	{

		try
		{
			$query = $this->db->query(" SELECT
											FAQ

										FROM app_info
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->row();

					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);

				if($result)
				{
					$this->response($data, 200);
				}
				else
				{
					$this->response($data, 404);
				}
		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}
	}

	public function getPrivacyPolicy_get()
	{

		try
		{
			$query = $this->db->query(" SELECT
											PrivacyPolicy

										FROM app_info
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->row();

					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);

				if($result)
				{
					$this->response($data, 200);
				}
				else
				{
					$this->response($data, 404);
				}
		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}

	}

	public function getTermOfUse_get()
	{

		try
		{
			$query = $this->db->query(" SELECT
											TermOfUse

										FROM app_info
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->row();

					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);

				if($result)
				{
					$this->response($data, 200);
				}
				else
				{
					$this->response($data, 404);
				}
		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}

	}

	public function getOurLocation_get()
	{

		try
		{
			$query = $this->db->query(" SELECT
											OurLocation

										FROM app_info
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->row();

					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);

				if($result)
				{
					$this->response($data, 200);
				}
				else
				{
					$this->response($data, 404);
				}
		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}

	}


	public function get_get()
	{

		try
		{
			$query = $this->db->query(" SELECT *
										FROM app_info
									");
			if($query===FALSE)
				throw new Exception();

				$result = $query->row();

					$data = array(
						'massage' => 'success',
						'error' => 'false',
						'data' => $result
					);

				if($result)
				{
					$this->response($data, 200);
				}
				else
				{
					$this->response($data, 404);
				}
		}
		catch(Exception $e)
		{

			print_r($this->db->_error_number());die;
		}
	}