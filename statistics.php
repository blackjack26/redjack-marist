<html>
<?php
 require('includes/connect_db.php');
 require('includes/header.php');
 validateAdminAccess();
?>


<body>
  <div class="table-container" id="stats-table">
    <h2>Statistics</h2>
    <p></p>            
    <table class="table table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr><th>Description</th><th>Value</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>Total Suggestions</td>
          <td><?php echo getStat("SELECT COUNT(*) FROM suggestions;")['COUNT(*)']; ?></td>
        </tr>
        <tr>
          <td>Total Unreviewed (%)</td>
          <td>
            <?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE reviewed = 0;")['COUNT(*)'] . " (" .
              round(getStat("SELECT COUNT(*) FROM suggestions WHERE reviewed = 0;")['COUNT(*)']/getStat("SELECT COUNT(*) FROM suggestions;")['COUNT(*)'] * 100, 3) . "%)"; 
            ?>
          </td>
        </tr>
        <tr>
          <td>Total Implemented (%)</td>
          <td>
            <?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE implemented = 1;")['COUNT(*)'] . " (" . 
              round(getStat("SELECT COUNT(*) FROM suggestions WHERE implemented = 1;")['COUNT(*)']/getStat("SELECT COUNT(*) FROM suggestions WHERE reviewed = 1;")['COUNT(*)'] * 100, 3) . "%)";
            ?>
            </td>
        </tr>
        <tr>
          <td>Total Not Implemented (%)</td>
          <td>
            <?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE implemented = 0 AND reviewed = 1;")['COUNT(*)'] . " (" . 
              round(getStat("SELECT COUNT(*) FROM suggestions WHERE implemented = 0 AND reviewed = 1;")['COUNT(*)']/getStat("SELECT COUNT(*) FROM suggestions WHERE reviewed = 1;")['COUNT(*)'] * 100, 3) . "%)"; 
            ?>
          </td>
        </tr>
        <tr>
          <td>Most Voted Category</td>
          <td><?php echo getStat("SELECT Category FROM suggestions ORDER BY up DESC LIMIT 1")["Category"]; ?></td>
        </tr>
        <tr>
          <td>Most Suggested Category</td>
          <td><?php echo getStat("SELECT Category, COUNT(*) as total FROM suggestions GROUP BY category ORDER BY total DESC LIMIT 1;")["Category"]; ?></td>
        </tr>
        <tr>
          <td>% Reviewed Popular Unimplemented</td>
          <td><?php echo round(getStat("SELECT COUNT(*) as num FROM suggestions WHERE up > down AND implemented = 0 AND reviewed = 1 AND up > 8;")['num']/getStat("SELECT COUNT(*) as num FROM suggestions WHERE up > down AND reviewed = 1 AND up > 8;")['num'] * 100, 3) . "%"; ?></td>
        </tr>
        <tr>
          <td>% Reviewed Unpopular Implemented</td>
          <td><?php echo round(getStat("SELECT COUNT(*) as num FROM suggestions WHERE up <= down AND implemented = 1 AND reviewed = 1 AND down > 8;")['num']/getStat("SELECT COUNT(*) as num FROM suggestions WHERE up <= down AND reviewed = 1 AND down > 8;")['num'] * 100, 3) . "%"; ?></td>
        </tr>
        <tr>
          <td>Quickest Response Time (hh:mm:ss)</td>
          <td><?php echo getStat("SELECT TIMEDIFF(f.post_date, s.post_date) AS diff FROM suggestions AS s, feedback AS f WHERE s.id = f.suggestionId ORDER BY diff LIMIT 1;")["diff"]; ?></td>
        </tr>
        <tr>
          <td>Longest Response Time (hh:mm:ss)</td>
          <td><?php echo getStat("SELECT TIMEDIFF(f.post_date, s.post_date) AS diff FROM suggestions AS s, feedback AS f WHERE s.id = f.suggestionId ORDER BY diff DESC LIMIT 1;")["diff"]; ?></td>
        </tr>
        <tr>
          <td>Oldest Unreviewed Suggestion</td>
          <td><?php echo getStat("SELECT post_date FROM suggestions WHERE reviewed = 0 LIMIT 1;")['post_date']; ?></td>
        </tr>
        <tr>
          <td>Total Votes</td>
          <td><?php echo getStat("SELECT SUM(up + down) AS total FROM suggestions;")['total']; ?></td>
        </tr>
        <tr>
          <td>Total Up Votes</td>
          <td><?php echo getStat("SELECT SUM(up) AS total FROM suggestions;")['total']; ?></td>
        </tr>
        <tr>
          <td>Total Down Votes</td>
          <td><?php echo getStat("SELECT SUM(down) AS total FROM suggestions;")['total']; ?></td>
        </tr>
        <tr>
          <td>Most Recent Vote</td>
          <td><?php echo getStat("SELECT vote_date FROM votes ORDER BY vote_date DESC LIMIT 1")['vote_date']; ?></td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" id="t-unrev" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE reviewed = 0;")['COUNT(*)']; ?>" />
    <input type="hidden" id="t-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE reviewed = 1 AND implemented = 0;")['COUNT(*)']; ?>" />
    <input type="hidden" id="t-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE reviewed = 1 AND implemented = 1;")['COUNT(*)']; ?>" />
    
    <!-- Bar Graph Total -->
    <input type="hidden" id="b-studlife" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Student Life';")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-admin" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Administration';")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-athl" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Athletics';")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-acad" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Academics';")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-house" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Housing';")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-safe" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Safety';")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-other" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Other';")['COUNT(*)']; ?>" />
    
    <!-- Bar Graph Implemented -->
    <input type="hidden" id="b-studlife-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Student Life' AND implemented = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-admin-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Administration' AND implemented = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-acad-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Academics' AND implemented = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-athl-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Athletics' AND implemented = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-house-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Housing' AND implemented = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-safe-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Safety' AND implemented = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-other-im" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Other' AND implemented = 1;")['COUNT(*)']; ?>" />
    
    <!-- Bar Graph Not Implemented -->
    <input type="hidden" id="b-studlife-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Student Life' AND implemented = 0 AND reviewed = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-admin-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Administration' AND implemented = 0 AND reviewed = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-acad-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Academics' AND implemented = 0 AND reviewed = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-athl-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Athletics' AND implemented = 0 AND reviewed = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-house-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Housing' AND implemented = 0 AND reviewed = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-safe-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Safety' AND implemented = 0 AND reviewed = 1;")['COUNT(*)']; ?>" />
    <input type="hidden" id="b-other-notim" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Other' AND implemented = 0 AND reviewed = 1;")['COUNT(*)']; ?>" />
    
    
    <!-- Radar Freshman -->
    <input type="hidden" id="f-studlife" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Student Life' AND class='Freshman';")['COUNT(*)']; ?>" />
    <input type="hidden" id="f-admin" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Administration' AND class='Freshman';")['COUNT(*)']; ?>" />
    <input type="hidden" id="f-acad" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Academics' AND class='Freshman';")['COUNT(*)']; ?>" />
    <input type="hidden" id="f-athl" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Athletics' AND class='Freshman';")['COUNT(*)']; ?>" />
    <input type="hidden" id="f-house" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Housing' AND class='Freshman';")['COUNT(*)']; ?>" />
    <input type="hidden" id="f-safe" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Safety' AND class='Freshman';")['COUNT(*)']; ?>" />
    <input type="hidden" id="f-other" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Other' AND class='Freshman';")['COUNT(*)']; ?>" />
    
    <!-- Radar Sophomore -->
    <input type="hidden" id="s-studlife" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Student Life' AND class='Sophomore';")['COUNT(*)']; ?>" />
    <input type="hidden" id="s-admin" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Administration' AND class='Sophomore';")['COUNT(*)']; ?>" />
    <input type="hidden" id="s-acad" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Academics' AND class='Sophomore';")['COUNT(*)']; ?>" />
    <input type="hidden" id="s-athl" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Athletics' AND class='Sophomore';")['COUNT(*)']; ?>" />
    <input type="hidden" id="s-house" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Housing' AND class='Sophomore';")['COUNT(*)']; ?>" />
    <input type="hidden" id="s-safe" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Safety' AND class='Sophomore';")['COUNT(*)']; ?>" />
    <input type="hidden" id="s-other" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Other' AND class='Sophomore';")['COUNT(*)']; ?>" />
    
    <!-- Radar Junior -->
    <input type="hidden" id="j-studlife" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Student Life' AND class='Junior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="j-admin" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Administration' AND class='Junior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="j-acad" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Academics' AND class='Junior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="j-athl" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Athletics' AND class='Junior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="j-house" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Housing' AND class='Junior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="j-safe" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Safety' AND class='Junior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="j-other" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Other' AND class='Junior';")['COUNT(*)']; ?>" />
    
    <!-- Radar Senior -->
    <input type="hidden" id="n-studlife" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Student Life' AND class='Senior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="n-admin" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Administration' AND class='Senior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="n-acad" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Academics' AND class='Senior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="n-athl" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Athletics' AND class='Senior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="n-house" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Housing' AND class='Senior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="n-safe" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Safety' AND class='Senior';")['COUNT(*)']; ?>" />
    <input type="hidden" id="n-other" value="<?php echo getStat("SELECT COUNT(*) FROM suggestions WHERE category = 'Other' AND class='Senior';")['COUNT(*)']; ?>" />
    
    <div id="feedback-chart">
      <h2>Feedback Stats</h2>
      <canvas id="donutChart"></canvas>
      
      <h2>Suggestion Stats</h2>
      <canvas id="barChart"></canvas>
      
      <h2>Suggestions by Class</h2>
      <canvas id="radarChart"></canvas>
      <div id="radar-legend" class="chart-legend"></div>
    </div>
  </div>

  
</body>

<script>
  $(function(){
    
    var dtx = document.getElementById("donutChart").getContext("2d");
    dtx.canvas.width = 210;
    dtx.canvas.height = 210;
    data = [
        {
            value: $("#t-unrev").val(),
            color: "#FDB45C",
            highlight: "#FFC870",
            label: "Unreviewed"
        },
        {
            value: $("#t-im").val(),
            color: "#46BFBD",
            highlight: "#5AD3D1",
            label: "Implemented"
        },
        {
            value: $("#t-notim").val(),
            color:"#F7464A",
            highlight: "#FF5A5E",
            label: "Unimplemented"
        }
    ];
    var myDoughnutChart = new Chart(dtx).Doughnut(data,{});
    
    var btx = document.getElementById("barChart").getContext("2d");
    btx.canvas.height = 400;
    btx.canvas.width = 350;
    
    data = {
    labels: ["Student Life", "Administration", "Academics", "Athletics", "Housing", "Safety", "Other"],
    datasets: [
            {
                label: "All Suggestions",
                fillColor: "#FDB45C",
                strokeColor: "#FDB45C",
                highlightFill: "#FFC870",
                highlightStroke: "#FFC870",
                data: [
                  $("#b-studlife").val(), 
                  $("#b-admin").val(), 
                  $("#b-acad").val(), 
                  $("#b-athl").val(), 
                  $("#b-house").val(), 
                  $("#b-safe").val(), 
                  $("#b-other").val()
                ]
            },
            {
                label: "Implemented Suggestions",
                fillColor: "#46BFBD",
                strokeColor: "#46BFBD",
                highlightFill: "#5AD3D1",
                highlightStroke: "#5AD3D1",
                data: [
                  $("#b-studlife-im").val(), 
                  $("#b-admin-im").val(), 
                  $("#b-acad-im").val(), 
                  $("#b-athl-im").val(), 
                  $("#b-house-im").val(), 
                  $("#b-safe-im").val(), 
                  $("#b-other-im").val()
                ]
            },
            {
                label: "Not Implemented Suggestions",
                fillColor: "#F7464A",
                strokeColor: "#F7464A",
                highlightFill: "#FF5A5E",
                highlightStroke: "#FF5A5E",
                data: [
                  $("#b-studlife-notim").val(), 
                  $("#b-admin-notim").val(), 
                  $("#b-acad-notim").val(), 
                  $("#b-athl-notim").val(), 
                  $("#b-house-notim").val(), 
                  $("#b-safe-notim").val(), 
                  $("#b-other-notim").val()
                ]
            }
        ]
    };
    new Chart(btx).Bar(data, {});
    
    var rtx = document.getElementById("radarChart").getContext("2d");
    rtx.canvas.height = 400;
    rtx.canvas.width = 350;
    
    data = {
        labels: ["Student Life", "Administration", "Academics", "Athletics", "Housing", "Safety", "Other"],
        datasets: [
            {
                label: "Freshman",
                fillColor: "rgba(46, 204, 113,0.2)",
                strokeColor: "rgba(39, 174, 96,1.0)",
                pointColor: "rgba(39, 174, 96,1.0)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [$("#f-studlife").val(), 
                  $("#f-admin").val(), 
                  $("#f-acad").val(), 
                  $("#f-athl").val(),
                  $("#f-house").val(), 
                  $("#f-safe").val(), 
                  $("#f-other").val()]
            },
            {
                label: "Sophomores",
                fillColor: "rgba(230, 126, 34,0.2)",
                strokeColor: "rgba(211, 84, 0,1.0)",
                pointColor: "rgba(211, 84, 0,1.0)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [$("#s-studlife").val(), 
                  $("#s-admin").val(), 
                  $("#s-acad").val(), 
                  $("#s-athl").val(), 
                  $("#s-house").val(), 
                  $("#s-safe").val(), 
                  $("#s-other").val()]
            },
            {
                label: "Juniors",
                fillColor: "rgba(155, 89, 182,0.2)",
                strokeColor: "rgba(142, 68, 173,1.0)",
                pointColor: "rgba(142, 68, 173,1.0)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [$("#j-studlife").val(), 
                  $("#j-admin").val(), 
                  $("#j-acad").val(),  
                  $("#j-athl").val(),
                  $("#j-house").val(), 
                  $("#j-safe").val(), 
                  $("#j-other").val()]
            },
            {
                label: "Seniors",
                fillColor: "rgba(52, 73, 94,0.2)",
                strokeColor: "rgba(44, 62, 80,1.0)",
                pointColor: "rgba(44, 62, 80,1.0)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: [$("#n-studlife").val(), 
                  $("#n-admin").val(), 
                  $("#n-acad").val(), 
                  $("#n-athl").val(), 
                  $("#n-house").val(), 
                  $("#n-safe").val(), 
                  $("#n-other").val()]
            }
        ]
    };
    var myRadarChart = new Chart(rtx).Radar(data, {});
    document.getElementById('radar-legend').innerHTML = myRadarChart.generateLegend();
  })
</script>
    
</html>