@extends('demo/layouts/index')
@section('title')
    Search
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" href="{{asset('css/demo.css')}}">
@stop
@section('content')
<div class="main">
    <div class="conver">
        <span class="lang_one">Tiếng Hre</span>
        <i class="fas fa-exchange-alt two-way-arrow"></i>
        <span class="lang_two">Tiếng Việt</span>
    </div>
    <div class="block_search">
        <input type="text" placeholder="Tìm kiếm tiếng hre" id="inputSearch">
        <ul>
        </ul>
    </div>
    <div class="block_hre">
        <textarea class="text_zone_hre" placeholder="Tiếng Hre" readonly="true"></textarea>
    </div>
    <div class="block_textarea">
        <div class="block_header">
            <div class="item">
                <select id="lang_mic" class="form-control">
                    <option value="vi-VN">Tiếng Việt</option>
                    <option value="en-US">Tiếng Anh</option>
                </select>
                <div class="icon_mic"><i class="fas fa-microphone"></i></div>
            </div>
            <div class="item">
                <select id="gender" class="form-control">
                    <option value="vi-vn">Giọng đọc tiếng Việt</option>
                    <option value="en-us">Giọng đọc tiếng Anh</option>
                </select>
                <div class="icon_heard"><i class="fas fa-volume-up"></i></div>
                <div class="icon_pause" id="icon_status">
                    <div class="isPause">
                        <i class="fas fa-pause"></i>
                    </div>
                    <div class="isPlay">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <audio src="" id="audio" hidden=""></audio>
            </div>
        </div>
        <textarea class="text_zone" placeholder="Tiếng Việt" readonly="true"></textarea>
    </div>
</div>
@stop
@section('footer-js')
<script src="https://code.responsivevoice.org/responsivevoice.js?key=XlsBiUwm"></script>
<script type="text/javascript">
    class SpeechRecognitionApi{
        constructor(options) {
            const SpeechToText = window.speechRecognition || window.webkitSpeechRecognition || window.mozSpeechRecognition || window.msSpeechRecognition;
            this.speechApi = new SpeechToText();
            this.speechApi.continuous = false;
            this.speechApi.interimResults = false;
            this.output = options.output ? options.output : document.createElement('div');
            this.speechApi.onresult = (event)=> { 
                //console.log(event);
                var resultIndex = event.resultIndex;
                var transcript = event.results[resultIndex][0].transcript;
                // console.log('transcript>>', transcript);
                // console.log(this.output)
                this.output.textContent = transcript;
            }
        }
        init(lang){
            if(lang != undefined)
                this.speechApi.lang = lang
            this.speechApi.start();
        }
        stop(){
            this.speechApi.stop();
        }
    }
    const micIcon = document.querySelector('.icon_mic');
    let textInputElement = document.querySelector('.text_zone');
    var speech = new SpeechRecognitionApi({
        output: textInputElement,
    });

    micIcon.onclick = function() {
        let lang = $("#lang_mic").val();
        speech.init(lang);
    }
    $("#lang_mic").change(() =>{
        let lang = $("#lang_mic :selected").text();
        alert(`Bạn đã chọn ngôn ngữ ${lang}`);
    })
</script>
<script type="text/javascript">
    myAudio = document.querySelector("#audio");
    $(".icon_heard").click(function(){
        let text = $(".text_zone").val();
        let gender = $("#gender").val();
        if(text != "")
        {
            let key = '3258dd6bc95f4859903b10b631da1eea';
            text = encodeURIComponent(text);
            let link = `http://api.voicerss.org/?key=${key}&hl=${gender}&src=${text}`;
            myAudio.setAttribute("src",link);
            myAudio.play();
        }
        else
            alert("Không có nội dung");
    });
    $("#icon_status").click(function(){
        if(myAudio.getAttribute("src") === "")
            alert("Vui lòng kích hoạt biểu tượng tai nghe");
        else
        {
            let text = $(".text_zone").val();
            let gender = $("#gender").val();
            if(myAudio.duration > 0)
            {
                if($("#icon_status").hasClass("icon_playing"))
                {
                    myAudio.play();
                    $("#icon_status").removeClass("icon_playing");
                    $("#icon_status").addClass("icon_pause");
                }
                else
                {
                    myAudio.pause();
                    $("#icon_status").removeClass("icon_pause");
                    $("#icon_status").addClass("icon_playing");
                }
            }
        }
    });
    myAudio.onended = function() {
        $("#icon_status").removeClass("icon_pause");
        $("#icon_status").addClass("icon_playing");
    };
    // $(".text_zone").select(function() {
    // 	let text = $(".text_zone").val();
        //  let gender = $("#gender").val();
    // 	responsiveVoice.speak(text,gender, {rate: 1, pitch: 1,volume: 1});
    // })
</script>
<script src="{{asset('js/search.js')}}"></script>
@stop