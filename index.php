<?php
// --- handle submit ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type = $_POST["type"] ?? "";          // outfit | ideas | presence | more
    $grow = $_POST["grow"] ?? "";          // web | art | care
    $text = trim($_POST["specific"] ?? ""); // only used when type === 'more'

    // allowlists
    $type_allowed = ["outfit","ideas","presence","more"];
    $grow_allowed = ["web","art","care"];

    // normalize
    $type = in_array($type, $type_allowed, true) ? $type : "more";
    $grow = in_array($grow, $grow_allowed, true) ? $grow : "art";

    // only keep text when user chose "something specific"
    if ($type !== "more") $text = "";

    // store flat-file (strip newlines)
    $clean  = str_replace(["\r","\n"], ' ', $text);
    $entry  = "Type: {$type}\n";
    $entry .= "Grow: {$grow}\n";
    $entry .= "Message: {$clean}\n";
    $entry .= "---\n";
    file_put_contents("responses.txt", $entry, FILE_APPEND | LOCK_EX);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>personal compliment jar</title>
  <style>
    :root{
      --emoji-type-outfit: "⭐️";
      --emoji-type-ideas: "🐝";
      --emoji-type-presence: "🐥";
      --emoji-type-more: "🦋";

      --emoji-grow-web: "🖤";
      --emoji-grow-art: "💚";
      --emoji-grow-care: "💛";
    }

    * { box-sizing: border-box; }
    body { 
        font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; 
        margin: 0; padding:1rem;
       color: mediumblue; 
       cursor: crosshair;
background: deeppink;
    }
 /*
    body.color-shift {
 *animation: bgshift 60s infinite alternate;
}

@keyframes bgshift {
  0%   { background: hotpink; }
  25%  { background: deeppink; }
  50%  { background: deepskyblue; }
  75%  { background: gold; }
  100% { background: orangered; }
}
*/

a{
    color: lightgreen;
    text-decoration: underline dotted indigo 2px;
}
marquee:hover{
    animation-state: paused;
    background: inherit;
}

    nav{
      max-width: 1500px;
      margin: 0 auto 0.5rem;
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 0.5rem;
      align-items: center;
    }
    .info-global{ display:flex; gap: 1rem; }
    .info-global > :last-child{ margin-left:auto; }

    .layout {
      max-width: 1500px; margin: 0 auto;
      display: grid; grid-template-columns: 1fr 2fr;
      gap: 0.5rem; align-items: start;
    }

    form { display: flex; flex-direction: column; }
    textarea { width:80%; }
    button { width: max-content; padding: .6rem 1rem; margin-top:1rem; }

    .responses { display: grid; gap: 1rem; }
    .info-header{ margin:0; padding:0; }

    .metrics{display:flex; gap: 4rem;}
    .counts-line{ font-size: .98rem; color:#222; display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; }
    .count{ display:inline-flex; align-items:baseline; gap:.35rem; }
    .dot{ opacity:.35; margin:0 .35rem; }
    .count.type.outfit::before   { content: var(--emoji-type-outfit); }
    .count.type.ideas::before    { content: var(--emoji-type-ideas); }
    .count.type.presence::before { content: var(--emoji-type-presence); }
    .count.type.more::before     { content: var(--emoji-type-more); }
    .count.grow.web::before  { content: var(--emoji-grow-web); }
    .count.grow.art::before  { content: var(--emoji-grow-art); }
    .count.grow.care::before { content: var(--emoji-grow-care); }
    .count::before{ font-size:1.05rem; line-height:1; }

    .all-responses{ display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-start; }
    .single-response{ width: clamp(220px,30%,320px); padding:1rem; border:1px solid indigo; border-radius:12px; color: cornsilk;}
    .what{ font-size:1.05rem; margin:0; }

    @media (max-width: 800px){
      .layout{ grid-template-columns:1fr; }
      .single-response{ width:100%; }
    }
  </style>
</head>
<body class="color-shift">

<nav>
  <div class="brand">
    <p>hiya :-)</p>
  </div>
  <div class="info-global">
    <p>Currently: <a href="#">webwork(love.chaos)</a>, <a href="#">art practice</a></p>
    <p>Other: <a href="#">instagram</a>, <a href="#">itch.io</a>, brainmap</p>
    <div class="contact">
      <p>teekundu@gmail.com</p>
    </div>
  </div>
</nav>

<div class="layout">
  <!-- LEFT: FORM -->
  <div>
    <form method="post" action="">
      <p><strong>drop me a compliment?</strong></p>
      <label><input type="radio" name="type" value="outfit"> i like your outfits ⭐️</label><br>
      <label><input type="radio" name="type" value="ideas"> i like ur ideas 🐝</label><br>
      <label><input type="radio" name="type" value="presence"> appreciated your presence 🐥</label><br>
      <label><input type="radio" name="type" value="more" checked> i have something specific!*</label><br>

      <p><strong>specifics (*only saves if you chose “specific”)</strong></p>
      <textarea name="specific" rows="4" placeholder="~ what did i do? how did it feel? why did it matter?"></textarea>

      <p><strong>which project are you excited to see grow?</strong></p>
      <label><input type="radio" name="grow" value="web"> web work, esp for others 🖤</label><br>
      <label><input type="radio" name="grow" value="art" checked> art / zines / workshops 💚</label><br>
      <label><input type="radio" name="grow" value="care"> care work, being there for people 💛</label>
      <label><input type="radio" name="grow" value="idk"> idk yet :-)</label>

      <button type="submit">Send</button>
    </form>

    <div class="updates">
       <br><br><br><br>
<p><strong>on display:
  </strong></p>
<p>
 
Goof Housekeeping<br>
Friendship Club, at ATC montreal<br>
Tabling at xyz, Mtl

<p>




</div>
  </div>

  <!-- RIGHT: RESPONSES -->
  <div class="responses">
    <div class="info-header">
      <h2>🦋 my compliment garden</h2>
      <p style="font-size:.9rem; margin-top:-.5rem;">
        this jar is for compliments only. occasionally moderated for my well-being ☠️
      </p>
    </div>

<?php
$type_labels = ["outfit"=>"outfits","ideas"=>"ideas","presence"=>"presence","more"=>"specific"];
$grow_labels = ["web"=>"web work","art"=>"art / zines / workshops","care"=>"care work"];

$entries = [];
if (file_exists("responses.txt")) {
    $lines = file("responses.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $current = ["type"=>"more","grow"=>"art","message"=>""];
    foreach ($lines as $line) {
        if ($line === "---") {
            if ($current["type"] || $current["message"]) $entries[] = $current;
            $current = ["type"=>"more","grow"=>"art","message"=>""];
            continue;
        }
        if (strpos($line, "Type:") === 0)     { $current["type"]    = trim(substr($line, 5)); }
        elseif (strpos($line, "Grow:") === 0) { $current["grow"]    = trim(substr($line, 5)); }
        elseif (strpos($line, "Message:") === 0){ $current["message"] = trim(substr($line, 8)); }
    }
    if ($current["type"] || $current["message"]) $entries[] = $current;
}
$entries = array_reverse($entries);

// tally counts
$type_counts = ["outfit"=>0,"ideas"=>0,"presence"=>0,"more"=>0];
$grow_counts = ["web"=>0,"art"=>0,"care"=>0];
foreach ($entries as $e){
  if (isset($type_counts[$e["type"]])) $type_counts[$e["type"]]++;
  if (isset($grow_counts[$e["grow"]])) $grow_counts[$e["grow"]]++;
}
?>

<div class="metrics">
    <!-- compliments count ribbon -->
    <div class="counts-line" aria-label="compliment counts">
      <span class="count type outfit"><?php echo $type_counts["outfit"]; ?></span>
      <span class="dot">·</span>
      <span class="count type ideas"><?php echo $type_counts["ideas"]; ?></span>
      <span class="dot">·</span>
      <span class="count type presence"><?php echo $type_counts["presence"]; ?></span>
      <span class="dot">·</span>
      <!-- <span class="count type more"><?php echo $type_counts["more"]; ?></span> -->
      <span class="sr-only" style="position:absolute;left:-10000px;">
        outfits <?php echo $type_counts["outfit"]; ?>, ideas <?php echo $type_counts["ideas"]; ?>,
        presence <?php echo $type_counts["presence"]; ?>, specific <?php echo $type_counts["more"]; ?>
      </span>
    </div>

    <!-- growth count ribbon -->
    <div class="counts-line" aria-label="growth counts">
      <span class="count grow web"><?php echo $grow_counts["web"]; ?></span>
      <span class="dot">·</span>
      <span class="count grow art"><?php echo $grow_counts["art"]; ?></span>
      <span class="dot">·</span>
      <span class="count grow care"><?php echo $grow_counts["care"]; ?></span>
      <span class="sr-only" style="position:absolute;left:-10000px; ">
        web <?php echo $grow_counts["web"]; ?>, art <?php echo $grow_counts["art"]; ?>, care <?php echo $grow_counts["care"]; ?>
      </span>
    </div>
</div>
    <!-- specific notes only -->
    <div class="all-responses">
      <?php
      foreach ($entries as $e) {
          if ($e["message"] === "") continue;
          $what = htmlspecialchars($e["message"]);
          echo '<div class="single-response">';
          echo '  <p class="what">'.$what.'</p>';
          echo '</div>';
      }
      ?>
    </div>
  </div>
</div>
<marquee style="position:absolute; bottom:5px;"><em>charm~~</em></marquee>
</body>
</html>
