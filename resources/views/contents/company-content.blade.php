
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                       
            <div class="clearfix"></div>
            <!--/heading-->
          
            <div class="white-box">
              <div class="pull-left"><h4>Alerts </h4></div>
              <div class="mockNwebsiteCreateBtn dis-flex hide">
                  <a href="#" class="pull-right  " ><button class="btn waves-effect waves-light btn-info mockOrderbtn" type="button"><i class="fa fa-plus"></i> Create Mockup Request</button></a>
                  <a href="#" class="pull-right  " ><button class="btn waves-effect waves-light btn-info webOrderbtn" type="button"><i class="fa fa-plus"></i> Create Website Order</button></a>
              </div>
              <div class="clearfix"></div>
              <br>
              <div class="steamline"> 
                       
                          <table  class="table table-striped ">
                              <tr><th>SL</th><th>Company</th></tr>	
                           @foreach($companies as $indexKey => $company)
                            <tr style="cursor:pointer" onClick="" ><td>{{$indexKey+1}}</td> <td><a href="/company?id={{$company->id}}">{{ucfirst($company->name)}}</a></td></tr>

                @endforeach
                          </table>
                          <div class="text-right"> {{ $companies->links() }} </div>          	
              </div>
            </div>
          </div>
          
         