<template>
  <div>
    <video
      id="localVideo"
      autoplay
      muted
    ></video>
    <button @click="publisher.initStream()">Iniciar Webcam</button>
    <video
      id="liveVideo"
      controls
      autoplay
    ></video>
  </div>
</template>

<script setup>
const publisher = reactive({
  init() {
    publisher.localVideo = document.getElementById("localVideo");
    publisher.liveVideo = document.getElementById("liveVideo");
    publisher.pc = null;
    console.log(publisher);
  },

  async initStream() {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: true,
      audio: true,
    });
    publisher.localVideo.srcObject = stream;
    publisher.pc = new RTCPeerConnection();
    stream.getTracks().forEach((track) => publisher.pc.addTrack(track, stream));
    const srsUrl = "wss://service-srs:8085/ws";

    const offer = await publisher.pc.createOffer();
    await publisher.pc.setLocalDescription(offer);

    // Enviar SDP para SRS via fetch (WebRTC publish)
    const resp = await fetch(
      `http://service-srs:1985/rtc/v1/publish/streamid?stream=webcamstream`,
      {
        method: "POST",
        body: offer.sdp,
        headers: { "Content-Type": "application/sdp" },
      }
    );
    const answerSDP = await resp.text();
    await pc.setRemoteDescription({ type: "answer", sdp: answerSDP });
  },
});

useHead({
  script: [
    {
      key: "hls",
      src: "https://cdn.jsdelivr.net/npm/hls.js@latest",
      onload() {
        publisher.init();
      },
    },
  ],
});
</script>
