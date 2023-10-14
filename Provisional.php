private function combination ($down, $up, $days, $conn) {
  $result = $conn -> query ("select date from numbers where number = $down limit $days order by date desc;");
  $dates = [];
while($row = $result -> fetch_assoc()){
    $dates [] = $row ["date"];
  }

$count = 0;
for($i=0; $i<count($dates); $i++) {
  $result = $conn -> query ("select id from numbers where number=$up and date = $dates[$i];");
  $count += $result -> num_rows;
}

//sacar porcentaje

//si el porcentaje es menor que tanto, excluir
}
