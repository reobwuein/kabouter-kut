

function Kabouter(){

	var _name,
		_voiceOptions = {}

	function CONSTRUCTOR(){
		_voiceOptions.amplitude = 300;
		_voiceOptions.pitch 	= 9999;
		_voiceOptions.speed 	= 100;
	}

	this.gender = "m";

	this.setName = function(name){
		_name = name;
	}

	this.setVoice = function(language, variant){
		_voiceOptions.voice	 	= language;
		_voiceOptions.variant 	= variant;
		meSpeak.loadVoice("javascript/vendor/mespeak/voices/"+_voiceOptions.voice+'.json');
	}

	this.say = function(string){
		meSpeak.speak(string, _voiceOptions);
		document.getElementById('shout-feed').innerHTML = string;
	}

	this.sayYourName = function(){
		this.say("Hello, my name is " + _name );
	}

CONSTRUCTOR();}
