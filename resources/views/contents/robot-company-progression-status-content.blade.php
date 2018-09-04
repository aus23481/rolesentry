
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                       
            <div class="clearfix"></div>
            <!--/heading-->
          
            <div class="white-box">
              <div class="pull-left"><h4>Robot Companies Progression Status </h4></div>
              
              <div class="clearfix"></div>
              <br>
              
              <div class="steamline"> 
                            
                    <div class="form-group">
                            <select onchange="window.location='/robot-company-progression-status?id='+this.value" id="progression_status" name="progression_status">
                                <option value="0">Select Status</option>
                                @foreach($robot_company_progression_status as $ps)
                                  
                                <option @if(isset($_REQUEST["id"])&&$_REQUEST["id"]==$ps->id) selected @endif value="{{$ps->id}}">{{$ps->name}}</option>
          
                                @endforeach
                            </select>
                        </div>


                          <table  class="table table-striped ">
                              <tr><th>SL</th><th>Company</th></tr>	
                           @foreach($robot_companies as $indexKey => $company)
                            <tr style="cursor:pointer" onClick="" ><td>{{$indexKey+1}}</td> <td><a href="/robot-company?id={{$company->id}}">{{ucfirst($company->company_name)}}</a></td></tr>

                @endforeach
                          </table>
                          <div class="text-right">  </div>          	
              </div>
            </div>
          </div>
          
         