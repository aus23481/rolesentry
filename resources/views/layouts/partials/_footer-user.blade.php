    
      <!-- footer -->
      <div class="footer-dark">
          <footer>
              <div class="container">
                  <p class="copyright">© 2018 Recruiter Intel LLC. All rights reserved.</p>
              </div>
          </footer>
      </div>
     <!-- footer -->
     <script>
		$('#reset-check-1').click(function(){
			$('#jobtype-clear').find('input[type=checkbox]:checked').removeAttr('checked');
		});
		
		$('#reset-check-2').click(function(){
			$('#location-clear').find('input[type=checkbox]:checked').removeAttr('checked');
		});
		
		$('#reset-check-3').click(function(){
			$('#notifs-clear').find('input[type=checkbox]:checked').removeAttr('checked');
		});
		
		/* $('#fave-btn').click(function(){
			$('#sidemenu').hide();
			$('#favorites').toggle("right");
			return false;
		});  */
		
		
		$("#togglebtn1").click(function () {
			$(".toggletext1").toggleClass('toggletext1b');
		});
		
		$("#togglebtn2").click(function () {
			$(".toggletext2").toggleClass('toggletext1b');
		});
		
		$("#togglebtn3").click(function () {
			$(".toggletext3").toggleClass('toggletext1b');
		});
		
		$("#togglebtn4").click(function () {
			$(".toggletext4").toggleClass('toggletext1b');
        });
        
        $("#togglebtn5").click(function () {
			$(".toggletext5").toggleClass('toggletext1b');
		});

	
		
		$('#show-search-filter-btn').click(function(){
			$('#search-filter-panel').slideToggle();
			return false;
		});
		$('#show-search-filter-btn2').click(function(){
			$('#search-filter-panel').slideToggle();
			return false;
		});
		/*
		$("#show-search-filter-btn").click(function () {
				$("#show-search-filter-text").fadeOut(function () {
					$("#show-search-filter-text").text(($("#show-search-filter-text").text() == 'Hide Search Filter') ? 'Show Search Filter' : 'Hide Search Filter').fadeIn();
				})
		}); */
		
		
	</script>	
 

      </body>
  </html>
