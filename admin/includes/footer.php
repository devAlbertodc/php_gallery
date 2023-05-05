  </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!--Textarea para editar foto-->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <!--Script para poder subir ficheros multiples -->
    <script src="js/dropzone.js"></script>

    <script src="js/scripts.js"></script>

    <script type="text/javascript">
      google.charts.load('visualization', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Views',    <?php echo $session->count;?>],
          ['Photos',   <?php echo Photo::count_all();?>],
          ['Users',    <?php echo User::count_all();?>],
          ['Comments', <?php echo Comment::count_all();?>]
        ]);

        var options = {
          legend: 'none',
          pieSliceText: 'label',
          title: 'Gallery Data',
          backgroundColor: 'transparent',
          colors: ['#337AB7','#5BB85C','#F0AD4E','#D9534E']
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
</body>

</html>
