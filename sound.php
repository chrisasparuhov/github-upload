<audio id="index_music" src="assets/sound/index_music.mp3" allow="autoplay" loop ></audio>
<script>
    var index_music = document.getElementById("index_music");
    function play_index_music() {
        index_music.currentTime = 0.01;
        index_music.volume = 0.3;
        index_music.play();
    }
</script>

<audio id="game_music" src="assets/sound/game_music.mp3" allow="autoplay" loop ></audio>
<script>
    var game_music = document.getElementById("game_music");
    function play_game_music() {
        game_music.currentTime = 0.01;
        game_music.volume = 0.3;
        game_music.play();
    }
</script>

<audio id="sound_achievement" src="assets/sound/achievement.mp3" allow="autoplay"></audio>
<script>
    var sound_achievement = document.getElementById("sound_achievement");
    function play_achievement() {
        sound_achievement.currentTime = 0.01;
        sound_achievement.volume = 0.3;
        sound_achievement.play();
    }
</script>




<audio id="on_error" src="assets/sound/error.mp3" allow="autoplay"></audio>
<script>
    var on_error = document.getElementById("on_error");
    function play_on_error() {
        on_error.currentTime = 0.01;
        on_error.volume = 1;
        on_error.play();
    }
</script>

<audio id="on_click" src="assets/sound/close_window.mp3" allow="autoplay"></audio>
<script>
    var on_click = document.getElementById("on_click");
    function play_on_click() {
        on_click.currentTime = 0.01;
        on_click.volume = 0.3;
        on_click.play();
    }
</script>

<audio id="on_hover" src="assets/sound/on_click.mp3" allow="autoplay" ></audio>
<script>
    var on_hover = document.getElementById("on_hover");
    function play_on_hover() {
        on_hover.currentTime = 0.01;
        on_hover.volume = 0.3;
        on_hover.play();
    }
</script>

<audio id="open_window" src="assets/sound/open_window.mp3" allow="autoplay"></audio>
<script>
    var open_window = document.getElementById("open_window");
    function play_open_window() {
        open_window.currentTime = 0.01;
        open_window.volume = 0.5;
        open_window.play();
    }
</script>

<audio id="keyboard" src="assets/sound/on_hover.wav" allow="autoplay"></audio>
<script>
    var keyboard = document.getElementById("keyboard");
    function play_keyboard() {
        keyboard.currentTime = 0.01;
        keyboard.volume = 0.5;
        keyboard.play();
    }
    $(document).keyup(function (e) {
        if (e.keyCode == 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 107, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122) {
            play_keyboard();
            return false;
        }
    });
</script>