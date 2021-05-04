           
           <!-- Content Header (Page header) -->
          <div class="content-header">
              <div class="container-fluid">
                  <div class="row mb-2">
                      <div class="col-sm-6">
                          <h1 class="m-0"><?php echo $title; ?></h1>
                      </div><!-- /.col -->
                      <div class="col-sm-6">
                          <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="#"><?php echo $breadcrumb; ?></a></li>
                              <li class="breadcrumb-item active"><?php echo $breadcrumb1; ?></li>
                          </ol>
                      </div><!-- /.col -->
                  </div><!-- /.row -->
              </div><!-- /.container-fluid -->
          </div>
          <!-- /.content-header -->     

          <section class="content">
            <div class="row">
              <div class="col-md-8">
                <!-- DIRECT CHAT -->
                <div class="card direct-chat direct-chat-warning">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo $data_tabel ?></h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                      </button>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages content">
                      <!-- Message. Default to the left -->

                      <div id="dumppy"></div>

                    </div>
                    <!--/.direct-chat-messages-->

                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <!-- <form action="#" method="post"> -->
                      <div class="input-group">
                        <input type="text" name="Message" placeholder="Type Message ..." class="form-control Message" id="Message">
                        <span class="input-group-append">
                          <?php foreach ($all_data as $row) : ?>
                          <input type="hidden" name="MemberID" id="MemberID" value="<?php echo $row->MemberID ?>">
                          <input type="hidden" name="ChatDID" id="ChatDID" value="<?php echo $row->ChatDID ?>">
                          <input type="hidden" name="EmpID" id="EmpID" value="<?php echo $row->EmpID ?>">
                          <?php endforeach ?>
                          <button type="button" class="btn btn-warning BtnSend" id="BtnSend">Send</button>
                        </span>
                      </div>
                    <!-- </form>-->                 
                    </div>
                  <!-- /.card-footer-->
                </div>
                <!--/.direct-chat -->
              </div>
              <!-- /.col -->
              <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h3 class="card-title">Member Info</h3>
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                      </div>
                  </div>
                  <div class="card-body box-profile">
                    <div class="row">
                      <?php foreach ($member as $row) : ?>
                      <div class="col-md-12">
                        <ul class="list-group list-group-unbordered mb-3">
                          <li class="list-group-item">
                            <b>Member ID</b> : <?php echo $row->MemberID  ?>
                          </li>
                          <li class="list-group-item">
                            <b>Name</b> : <?php echo $row->FullName  ?>
                          </li>
                          <li class="list-group-item">
                            <b>Email</b> : <?php echo $row->Email  ?>
                          </li>
                          <li class="list-group-item">
                            <b>Gender</b> : <?php echo $row->Gender  ?>
                          </li>
                          <li class="list-group-item">
                            <b>Mobile Phone</b> : <?php echo $row->CountryPrefixNo.$row->MobilePhoneNo?>
                          </li>
                        </ul>
                      </div>
                      <!-- /.col -->
                      <?php endforeach ?>
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.card-body -->
                </div>
              <!-- /.card -->
              </div>
              
            </div>
            <!-- /.row -->
          </section>

          <script type="text/javascript">


          //MESSAGE CHAT JS

          $('.Message').keypress(function(event){
              var keycode = (event.keyCode ? event.keyCode : event.which);
              if(keycode == '13'){
                 sendTxtMessage($(this).val());
              }
          });

          $('.BtnSend').click(function(){
                 sendTxtMessage($('.Message').val());
          });

          function ScrollDown(){
              var elmnt = document.getElementById("content");
              var h = elmnt.ScrollDown;
             $('#content').animate({ScrollTop: h}, 0);
          }

          function DisplayMessage(Message){
              var MemberID = $('#MemberID').val();
              var EmpID = $('#EmpID').val();
              
                  var str = '<div class="direct-chat-msg right">';
                          str+='<div class="direct-chat-infos clearfix">';
                           str+='<span class="direct-chat-name float-right">'+EmpID ;
                           str+='</span><span class="direct-chat-timestamp float-left"></span>'; //23 Jan 2:05 pm
                           str+=' <img class="direct-chat-img" src="<?= base_url('assets') ?>/dist/img/avatar5.png" alt="Message user image">';
                           str+='<div class="direct-chat-text  bg-warning">'+Message;
                           str+='</div></div>';
                  $('#dumppy').append(str);
          }

          function sendTxtMessage(Message){
          var MessageTXT = Message.trim();

          if(MessageTXT!='')
          {
              //console.log(message);
              DisplayMessage(MessageTXT);
              
                      var MemberID = $('#MemberID').val();
                      $.ajax({
                                dataType : "json",
                                type : 'post',
                                data : {MessageTXT : MessageTXT, MemberID : MemberID},
                                url: '<?php echo base_url('backend/SupportChat/send_text_message')?>',
                                success:function(data)
                                {
                                  GetChatHistory(ChatDID)      
                                },
                                error: function (jqXHR, status, err) {
                                   // alert('Local error callback');
                                }
                          });
                          
              
              
                    $('.Message').val('');
                    $('.Message').focus();
                }else{
                    $('.Message').focus();
                }
            }

            function GetChatHistory(MemberID){
                $.ajax({
                          //dataType : "json",

                          url: '<?php echo base_url('backend/SupportChat/get_chat')?>/'+MemberID,
                          success:function(data)
                          {
                            $('#dumppy').html(data); 
                          },
                          error: function (jqXHR, status, err) {
                             // alert('Local error callback');
                          }
                    });
            }


            setInterval(function(){ 
                var MemberID = $('#MemberID').val();
                if(MemberID!=''){
                    GetChatHistory(MemberID);
                }
            }, 500); //2600

            //END MESSAGE CHAT
            //=======================================================================================================


           



          </script>