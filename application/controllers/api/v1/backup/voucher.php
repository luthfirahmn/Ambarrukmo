public function vouchermember_post()
  {

    if (empty($this->post("MemberID"))) 
    {

        $response = array(
          'message' => 'Invalid params',
          'error' => true,
        );
        $this->response($response, 400);
    }

      try
      {

         $MemberID = $this->post("MemberID");
         $VoucherCode = $this->post("VoucherCode");
         /*$query = $this->db->query(" SELECT *
                                          tpoint.DID AS pDID
                                          ,tpoint.MemberID AS pMemberID
                                          ,
                                          ,CONCAT('VOUCHER/',VoucherIMG) AS VoucherPath
                                      FROM tr_point AS tpoint
                                      LEFT JOIN ms_member AS member
                                      WHERE tpoint.MemberID ='{$MemberID}'
                                      AND tpoint.VoucherID != '0'

                               ");*/
         $query = $this->db->query(" SELECT * 
                                      ,tpoint.DID AS pDID
                                      ,tpoint.MemberID AS pMemberID
                                      ,tpoint.VoucherID AS pVoucherID
                                      ,tpoint.VoucherCode AS pVoucherCode
                                      ,CONCAT('VOUCHER/',VoucherIMG) AS VoucherPath
                                      FROM tr_point AS tpoint
                                      LEFT JOIN ms_voucher AS voucher ON voucher.DID = tpoint.VoucherID

                                      WHERE tpoint.MemberID ='{$MemberID}'
                                      AND tpoint.VoucherCode  ='{$VoucherCode}'

         ");
        if($query===FALSE)
          throw new Exception();

            $result = $query->row();
            $voucherbt  = date("d M", strtotime($result->VoucherBT));
            $exptime  = date("d M Y", strtotime($result->ExpiredTime));
            $ExpiredVoucher = $voucherbt.' - '.$exptime;

            $data = array(
              'massage' => 'success',
              'error' => 'false',
              'data' => array('DID'               => $result->pDID,
                              'MemberID'          => $result->pMemberID,
                              'VoucherID'         => $result->pVoucherID,
                              'VoucherCode'       => $result->pVoucherCode,
                              'VoucherName'       => $result->VoucherName, 
                              'VoucherPath'       => $result->VoucherPath, 
                              'VoucherShortNote'  => $result->VoucherShortNote, 
                              'VoucherNote'       => $result->VoucherNote, 
                              'ExpiredVoucher'    => $ExpiredVoucher, 
                              )
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