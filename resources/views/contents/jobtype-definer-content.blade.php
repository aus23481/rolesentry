
        
        <div class="col-md-9 col-lg-9 col-xs-12">          
                
     <div class="clearfix"></div>
     <!--/heading-->
    
     <div class="white-box">
        <h3>Words For Job  Types</h3>    
       <div class="clearfix"></div>
       <br>
       <div class="steamline" > 
                
                       
                        
                        <div class="form-group">
                            
                             @if(isset($job_types))
                               @foreach($job_types as $type)
                                <div><h4>{{$type->name}}</h4>   </div>
                                 <div class="col-md-12">
                                <hr />                        
                                @foreach($type->words as $word) 
                                <div class="col-sm-12">{{$word->word}} <a href="/delete-jobtype-word/{{$word->id}}"><i class="fa fa-trash-o"></i></a> 
                               
                                  @foreach($type->subtypes as $subtype)
                                  &nbsp;&nbsp; &nbsp;&nbsp; 
                                  <input type="checkbox" word-id="{{$word->id}}" name="subtype_{{$subtype->name}}" @if(isset($job_type_word_job_subtypes[$word->id."_".$subtype->id]) && $job_type_word_job_subtypes[$word->id."_".$subtype->id] == 1 ) checked @endif  value="{{$subtype->id}}"> 
                                  
                                  {{$subtype->name}} &nbsp;&nbsp;
                                    
                                  @endforeach
                                </div>  
                                @endforeach
                                <hr />
                              </div> 
                              <form class="form-inline"  action="{{ URL::to('add-jobtype-word')}}"   id="bantext-form" enctype="multipart/form-data" method="get">
                              
                               
                                
                                  {{ csrf_field() }} 
                                <input type="hidden" name="job_type_id" value="{{$type->id}}">     
                                    <div  class="form-group">                                          
                                            <input id="word" name="word" placeholder="Add Word" size="40%" id="name" required="required" class="form-control" type="text" value="<?php if(isset($name)) print $name; ?>">
                                        <button type="submit" id="btn_add_word" class="btn btn-default" data-dismiss="modal">
                                                    Save Word 
                                            </button>
                                    </div>
                                </form>
                                                       

                                @endforeach 
                             @endif 
                             
                             

                        </div>
                  
                            
       </div>
     </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    