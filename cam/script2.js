const video = document.querySelector("#video");

//setTimeout(autoCapture, 2000);
//setTimeout(saveImage, 4000);

// Basic settings for the video to get from Webcam
const constraints = {
  audio: false,
  video: {
    facingMode: 'environment',
    width: 500,
    height: 500
  }
};

// This condition will ask permission to user for Webcam access
if (navigator.mediaDevices.getUserMedia) {
  navigator.mediaDevices.getUserMedia(constraints)
    .then(function(stream) {
      video.srcObject = stream;
    })
    .catch(function(err0r) {
      console.log("Something went wrong!");
    });
}

function stop(e) {
  const stream = video.srcObject;
  const tracks = stream.getTracks();

  for (let i = 0; i < tracks.length; i++) {
    const track = tracks[i];
    track.stop();
  }
  video.srcObject = null;
}

function autoCapture () {
  const context = canvas.getContext('2d');
  // Capture the image into canvas from Webcam streaming Video element
  context.drawImage(video, 0, 0);
}

function manualCapture() {
  autoCapture();
  saveImage();

}

function saveImage() {
    var canvasData = canvas.toDataURL("image/png");
    var xmlHttpReq = false;
  
    if (window.XMLHttpRequest) {
      ajax = new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
      ajax = new ActiveXObject("Microsoft.XMLHTTP");
    }
  
    ajax.open("POST", "post.php", false);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.onreadystatechange = function() {
      console.log(ajax.responseText);
    }
    ajax.send("imgData=" + canvasData);
  }

// Below code to capture image from Video tag (Webcam streaming)
const btnCapture = document.querySelector("#btnCapture");
const canvas = document.getElementById('canvas');

document.querySelector("#takepic").addEventListener("click", manualCapture);
