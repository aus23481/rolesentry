
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
          
          <div class="bg-title">
              <div class="row">
				<div class="col-md-4 col-lg-4 col-xs-12">  
					<h4><a class="waves-effect theme-clr-txt  m-t-10" href="#"><i class="fa fa-envelope"></i> <i>We Have News for You</i> !</a></h4>
				</div>
				<div class="col-md-8 col-lg-8 col-xs-12">  
				 <p>...</p>
				</div>
			  </div>
          </div>
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
							<tr><th>SL</th><th>Company</th><th>Title</th><th>Created</th></tr>	
						 @foreach($alerts as $indexKey => $alert)
						  <tr style="cursor:pointer" onClick="" ><td>{{$indexKey+1}}</td> <td><a href="/alert?id={{$alert->sl}}">{{ucfirst($alert->company)}}</a></td><td><a href="/alert?id={{$alert->sl}}">{{$alert->title}}</a></td><td>{{$alert->created_at}}</td></tr>
              @endforeach
						</table>	
						<div class="text-right"> {{ $alerts->links() }} </div>
            </div>
          </div>
        </div>
        
       