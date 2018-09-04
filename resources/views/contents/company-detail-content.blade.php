<?php

	$user = Auth::user();
	
?>  
  
<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                <div class="panel-heading head-desc">
                    <h4> {{ucwords($company->name)}}  </h4>
                </div>

                <div class="panel-body">

                    

                    <div class="steamline"> 
                        <h3>Alerts of {{ucwords($company->name)}} </h3>
                        <hr>
                        <table  class="table table-striped ">
                            <tr><th>SL</th><th>Title</th></tr>	
                         @foreach($company->alerts as $indexKey => $alert)
                          <tr style="cursor:pointer" onClick="" ><td>{{$indexKey+1}}</td> <td><a href="#">{{ucfirst($alert->job->title)}}</a></td></tr>
                         @endforeach
                        </table>	
            </div>

                    <div class="clearfix"></div>
                    <br>
                    <div class="steamline"> 
                                <h3>Jobs of {{ucwords($company->name)}} </h3>
                                <hr>
                                <table  class="table table-striped ">
                                    <tr><th>SL</th><th>Title</th><th>Created</th></tr>	
                                 @foreach($company->jobs as $indexKey => $job)
                                  <tr style="cursor:pointer" onClick="" ><td>{{$indexKey+1}}</td> <td><a href="/alert?id={{$job->company->name}}">{{ucfirst($job->title)}}</a></td><td><a href="#">{{$job->created_at}}</a></td></tr>

                                 @endforeach
                                </table>	
                    </div>
                    
                    

 
                </div>
            </div>  
</div>
        
