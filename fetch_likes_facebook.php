<?php
require_once 'vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '<app_id>',
  'app_secret' => '<app_secret>',
  'default_graph_version' => 'v2.5',
  'default_access_token' => '<access_token>', // optional
]);



try {
  // Get the Facebook\GraphNodes\GraphUser object for the current user.
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->get('/1129753540390350/likes');
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
$commentsEdge = $response->getGraphEdge();
$file = fopen("likes.csv","w");

$pageCount=0;
  do {
    foreach ($commentsEdge as $photo) {
     // var_dump($photo->asArray());
      echo $photo;
      fputcsv($file,explode(',',$photo));

      // Deep pagination is supported on child GraphEdge's
    $pageCount++;
  }
  } while ($pageCount < 100000000000 && $commentsEdge = $fb->next($commentsEdge));
  fclose($file);

?>