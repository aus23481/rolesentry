
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                
     <div class="clearfix"></div>
     <!--/heading-->
    
     <div class="white-box">
       <div class="pull-left"><h4>Add a New Robot Company By Name </h4></div>
       
       <div class="clearfix"></div>
       <br>
       <div class="steamline"> 
                
                    <form  action="{{ URL::to('robotcms/save-company-by-career-page')}}"   id="company-form" enctype="multipart/form-data" method="post">
                        {{ csrf_field() }}
                       
                        <div class="form-group">
                                <label for="website">Name</label>     
                            <input class="form-control" name="name" size="60%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">
                                                                
                        </div>
                                
                        <div class="form-group">
                                <label for="website">Website</label>                                    
                            <input name="website" class="form-control" size="60%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">
                                                                                                
                        </div>

                        <div class="form-group">
                                <label for="career_page">Career Page</label>    
                            <input name="career_page" class="form-control" size="60%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">
                                                                
                        </div>
                        
                        <div  class="form-group">            
                              
                            <button type="submit" id="btn_add_company" class="btn btn-default" data-dismiss="modal">
                                        Save Company
                                </button>
                        </div>

                    </form>
                              
       </div>
     </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    