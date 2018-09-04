        <div class="col-md-3 col-lg-3 col-xs-12">
          <div class="white-box">
            <h3>Recent Companies</h3>
            <div class="steamline">

              <table class="table table-striped">
                  <tr><th>ID</th><th>Name</th></tr>
             @foreach($companies as $company)   
              <tr><td>{{$company->id}}</td><td>{{$company->name}}</td></tr>
              @endforeach  
              
                              
              </table>
              <div class="text-right"> {{ $companies->links() }} </div>
                

            </div>
          </div>
        </div>
 