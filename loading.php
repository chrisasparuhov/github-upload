<style>
    .seven {
        position: relative;
        z-index:9999;
        border: solid #40c0fb 1px;
        width: 154px;
        top:50%;
        height: 20px;
        border-radius: 7px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 70px;
    }

    .seven:before {
        content: "";
        position: absolute;
        width: 146px;
        height: 16px;
        top: 2px;
        left: 2px;
        background-image: linear-gradient(#0a1d26, black)!important;
        border-radius: 4px;
        animation: loader7AnimationBefore 2s linear infinite;
    }

    .seven:after {
        color:#FEFEFE;
        font-family: 'Electrolize'!important;
        font-size:12px;
        font-weight: bold;
        text-shadow: 0 0 4px rgba(161,236,251,0.65);
        text-transform: uppercase;
        content: "Loading...";
        position: absolute;
        width: 150px;
        color: #40c0fb;
        top:0px;
        text-align: center;
        animation: loader7AnimationAfter 2s linear infinite;
    }

    @keyframes loader7AnimationBefore {
        0% {
            width: 0%;
        }
        15% {
            width: 0%;
        }
        85% {
            width: 146px;
        }
        100% {
            width: 146px;
        }
    }

    @keyframes loader7AnimationAfter {
        0% {
            content: "Loading";
        }
        25% {
            content: "Loading.";
        }
        50% {
            content: "Loading..";
        }
        75% {
            content: "Loading...";
        }
        100% {
            content: "Loading...";
        }
    }
</style>
<div class='loading'>

    <div class="seven"></div>

</div>
<script type='text/javascript'>
    $(document).ready(function () {
        $('.loading').delay("1750").fadeOut("fast");
    });
</script>