 
    <footer class="footer text-center"> 2018 &copy; rolesentry.com </footer>
   
</div>
<!-- /#wrapper -->
<script>
	function TopUserName() {
		var x = document.getElementById("TopUserName");
		if (x.style.display === "none") {
			x.style.display = "block";
		} else {
			x.style.display = "none";
		}
	}
</script>

<!-- /#wrapper -->
<!-- jQuery -->
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!--Nice scroll JavaScript -->
<script src="js/jquery.nicescroll.js"></script>
<script src="bower_components/datatables/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js"></script>
<!--<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script> -->
<script>

$(function(){
  //$('#keywords').tablesorter(); 
});

///*
  $(document).ready(function() {
    
   var dataSrc = [];

   var table = $('.data-table').DataTable({
      'initComplete': function(){
         var api = this.api();

         // Populate a dataset for autocomplete functionality
         // using data from first, second and third columns
         api.cells('tr', [0, 1, 2]).every(function(){
            // Get cell data as plain text
            var data = $('<div>').html(this.data()).text();           
            if(dataSrc.indexOf(data) === -1){ dataSrc.push(data); }
         });
         
         // Sort dataset alphabetically
         dataSrc.sort();
        
         // Initialize Typeahead plug-in
         $('.dataTables_filter input[type="search"]', api.table().container())
            .typeahead({
               source: dataSrc,
               afterSelect: function(value){
                  api.search(value).draw();
               }
            }
         );
      }
   });

});  

//*/



//$("#data-table_filter").addClass("pull-right");
  </script>
<!--Wave Effects -->
<script src="js/custom.js"></script>
<!-- Custom Theme JavaScript -->
<script src="js/myadmin.js"></script>
</body>
</html>
