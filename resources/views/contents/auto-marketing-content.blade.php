
         
          
        <div class="col-md-9 col-lg-9 col-xs-12">          
                
     <div class="clearfix"></div>
     <!--/heading-->
    
     <div class="white-box">
        <h3>Automated Marketing</h3>    
       <div class="clearfix"></div>
       <br>
       <div class="steamline1" > 
                
                       
                        
                        <div class="form-group">
                            
                            <h3>Email Activity</h3>
                            <table    class="table table-striped data-table">
                                <thead>
                                  <tr><th><span>Name</span></th> 
                                    <th><span>5 days ago</span></th> 
                                    <th><span>4 days ago</span></th> 
                                    <th><span>3 days ago</span></th> 
                                    <th><span>2 days ago</span></th> 
                                    <th><span>1 day ago</span></th>
                                    <th><span>Today</span></th>
                                    <th><span>Total</span></th>  
                                  </tr>
                              </thead>
                              <tbody>
                              <?php
                                $all_names = [];
                              ?>
                              @foreach($user_email_interactions as $interaction)
                                 <?php 
                                 
                                 $total = 0;
                                 
                                 if(isset($days[5][$interaction->user_id])) $total += $days[5][$interaction->user_id];
                                 if(isset($days[4][$interaction->user_id])) $total += $days[4][$interaction->user_id];
                                 if(isset($days[3][$interaction->user_id])) $total += $days[3][$interaction->user_id];
                                 if(isset($days[2][$interaction->user_id])) $total += $days[2][$interaction->user_id];
                                 if(isset($days[1][$interaction->user_id])) $total += $days[1][$interaction->user_id];
                                 if(isset($days[0][$interaction->user_id])) $total += $days[0][$interaction->user_id];
                                 $all_names[] = $interaction->user->name."#".$interaction->user_id;
                                // $all_names[]["user_id"] = $interaction->user_id;

                                 //$all_names[]["total"] = $total;

                                 ?>
                                <tr>
                                    <td>@if(isset($interaction->user)) {{$interaction->user->name}} @endif</td>
                                    <td>@if(isset($days[5][$interaction->user_id])) {{$days[5][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days[4][$interaction->user_id])) {{$days[4][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days[3][$interaction->user_id])) {{$days[3][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days[2][$interaction->user_id])) {{$days[2][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days[1][$interaction->user_id])) {{$days[1][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days[0][$interaction->user_id])) {{$days[0][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($total)) {{$total}} @endif</td>
                                    
                                </tr>
                              
                              @endforeach
                              </tbody>
                            </table>
                             
                             

                        </div>


                        <div class="form-group">
                            
                            <h3>Platform Activity</h3>
                            <table    class="table table-striped data-table">
                                <thead>
                                  <tr><th><span>Name</span></th> 
                                    <th><span>5 days ago</span></th> 
                                    <th><span>4 days ago</span></th> 
                                    <th><span>3 days ago</span></th> 
                                    <th><span>2 days ago</span></th> 
                                    <th><span>1 day ago</span></th>
                                    <th><span>Today</span></th>
                                    <th><span>Total</span></th>  
                                  </tr>
                              </thead>
                              <tbody>
                              @foreach($tracking_user_interaction as $interaction)
                                 <?php 
                                 $total = 0;
                                 
                                 if(isset($days_platform[5][$interaction->user_id])) $total += $days_platform[5][$interaction->user_id];
                                 if(isset($days_platform[4][$interaction->user_id])) $total += $days_platform[4][$interaction->user_id];
                                 if(isset($days_platform[3][$interaction->user_id])) $total += $days_platform[3][$interaction->user_id];
                                 if(isset($days_platform[2][$interaction->user_id])) $total += $days_platform[2][$interaction->user_id];
                                 if(isset($days_platform[1][$interaction->user_id])) $total += $days_platform[1][$interaction->user_id];
                                 if(isset($days_platform[0][$interaction->user_id])) $total += $days_platform[0][$interaction->user_id];
                                 if(!in_array($interaction->user->name."#".$interaction->user_id, $all_names))
                                
                                 if(isset($interaction->user->name)&& isset($interaction->user_id))
                                 $all_names[] = $interaction->user->name."#".$interaction->user_id;
                                 //$all_names[]["user_id"] = $interaction->user_id;
                                 //$all_names[]["total"] = $total;
                                 ?>
                                 
                                <tr>
                                    <td class="lalign">@if(isset($interaction->user)) {{$interaction->user->name}} @endif</td>
                                    <td>@if(isset($days_platform[5][$interaction->user_id])) {{$days_platform[5][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days_platform[4][$interaction->user_id])) {{$days_platform[4][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days_platform[3][$interaction->user_id])) {{$days_platform[3][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days_platform[2][$interaction->user_id])) {{$days_platform[2][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days_platform[1][$interaction->user_id])) {{$days_platform[1][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($days_platform[0][$interaction->user_id])) {{$days_platform[0][$interaction->user_id]}} @endif</td>
                                    <td>@if(isset($total)) {{$total}} @endif</td>
                                    
                                </tr>
                              
                              @endforeach
                            </tbody>
                            </table>
                             
                             

                        </div>


                        <div class="form-group">
                            
                            <h3>Total Activity</h3>
                            <table    class="table table-striped data-table">
                              <thead>
                                <tr><th><span>Name</span></th> 
                                  <th><span>5 days ago</span></th> 
                                  <th><span>4 days ago</span></th> 
                                  <th><span>3 days ago</span></th> 
                                  <th><span>2 days ago</span></th> 
                                  <th><span>1 day ago</span></th>
                                  <th><span>Today</span></th>
                                  <th><span>Total</span></th>  
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($all_names as $name)
                                  <?php
                                  $total = 0;
                                  $total5 = 0;
                                  $total4 = 0;
                                  $total3 = 0;
                                  $total2 = 0;
                                  $total1 = 0;
                                  $total0 = 0;

                                  $user_id = explode("#",$name)[1];
                                  
                                  if(isset($days[5][$user_id])) $total5 += $days[5][$user_id];
                                  if(isset($days_platform[5][$user_id])) $total5 += $days_platform[5][$user_id];

                                  if(isset($days[4][$user_id])) $total4 += $days[4][$user_id];
                                  if(isset($days_platform[4][$user_id])) $total4 += $days_platform[4][$user_id];

                                  if(isset($days[3][$user_id])) $total3 += $days[3][$user_id];
                                  if(isset($days_platform[3][$user_id])) $total3 += $days_platform[3][$user_id];

                                  if(isset($days[2][$user_id])) $total2 += $days[2][$user_id];
                                  if(isset($days_platform[2][$user_id])) $total2 += $days_platform[2][$user_id];

                                  if(isset($days[1][$user_id])) $total1 += $days[1][$user_id];
                                  if(isset($days_platform[1][$user_id])) $total1 += $days_platform[1][$user_id];

                                  if(isset($days[0][$user_id])) $total0 += $days[0][$user_id];
                                  if(isset($days_platform[0][$user_id])) $total0 += $days_platform[0][$user_id];

                                  $total = $total5 + $total4 + $total3 + $total2 + $total1 + $total0;


                                  
                                  ?>
                                  
                                  <tr>
                                    <td class="lalign" >@if(isset($name)) {{explode("#",$name)[0]}} @endif</td>
                                    <td> {{$total5}}  </td>
                                    <td>{{$total4}} </td>
                                    <td>{{$total3}} </td>
                                    <td>{{$total2}} </td>
                                    <td>{{$total1}} </td>
                                    <td>{{$total0}} </td>
                                    <td>{{$total}} </td>
                                    
                                </tr>
                              
                              @endforeach
                              </tbody>
                            </table>
                             
                             

                        </div>

                        <div class="form-group">
                           <h3>  User Not in Platform but in Email activity in last 5 days </h3>
                            <table    class="table table-striped">
                              <tr><th><strong>Name</strong></th><th><strong>Action</strong></th></tr>

                              @foreach($user_not_in_platform_but_in_email_action as $user)
                               
                               <tr>
                                 <td>@if(isset($users_uei[$user->user_id])) {{$users_uei[$user->user_id]}} @endif </td>
                                 <td><button onclick="sendMailToInteractionUser({{$user->user_id}})" type="button" class="btn btn-primary"> Send Email </button></td>
                                </tr>
                              @endforeach

                          </table>

                        </div>



                        <div class="form-group">
                            <h3>  Users having more than 500 both interactions </h3>
                             <table    class="table table-striped">
                               <tr><th><strong>Name</strong></th><th><strong>Action</strong></th></tr>
 
                               @foreach($all_names as $name)
                                <?php
                                      $user_id = explode("#",$name)[1];
                                      $name = explode("#",$name)[0];
                                      $total = 0;
                                      if(isset($tui_count_total[$user_id])) $total += $tui_count_total[$user_id];
                                      if(isset($uei_count_total[$user_id])) $total += $uei_count_total[$user_id];



                                ?>
                               @if($total>100)       
                                <tr>
                                  <td>{{$name}} </td>
                                  <td><button onclick="sendMailToInteractionUser({{$user_id}})" type="button" class="btn btn-primary"> Send Email </button></td>
                                 </tr>
                                @endif
                                 @endforeach
 
                           </table>
 
                         </div>
                  
                            
       </div>
     </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    