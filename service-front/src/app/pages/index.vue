<template>
  <div>
    <video
      id="localVideoElem"
      autoplay
      muted
    ></video>

    <video
      id="liveVideoElem"
      controls
      autoplay
      playsinline
    ></video>

    <br />
    <button @click="publisher.initPublish()">initPublish</button>
    <button @click="publisher.initPlay()">initPlay</button>
  </div>
</template>

<script setup>
const publisher = reactive({
  init() {
    //
  },

  pcPublish: null,
  pcPlay: null,

  async initPublish() {
    const localVideoElem = document.querySelector("#localVideoElem");

    publisher.pcPublish = new RTCPeerConnection({
      iceServers: [{ urls: "stun:stun.l.google.com:19302" }],
    });

    const pc = publisher.pcPublish;
    pc.addTransceiver("video", { direction: "sendonly" });
    pc.addTransceiver("audio", { direction: "sendonly" });

    const stream = await navigator.mediaDevices.getUserMedia({
      video: true,
      audio: true,
    });
    localVideoElem.srcObject = stream;

    stream.getTracks().forEach((t) => pc.addTrack(t, stream));

    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);

    await new Promise((resolve) => {
      if (pc.iceGatheringState === "complete") return resolve();
      pc.onicegatheringstatechange = () => {
        if (pc.iceGatheringState === "complete") resolve();
      };
    });

    const res = await fetch("http://localhost:1985/rtc/v1/publish/", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        api: "http://localhost:1985/rtc/v1/publish/",
        streamurl: "webrtc://localhost/live/webcam",
        sdp: offer.sdp,
        clientip: null,
      }),
    });

    const data = await res.json();
    await pc.setRemoteDescription({ type: "answer", sdp: data.sdp });
  },

  async initPlay() {
    const video = document.querySelector("#liveVideoElem");

    publisher.pcPlay = new RTCPeerConnection({
      iceServers: [{ urls: "stun:stun.l.google.com:19302" }],
    });

    const pc = publisher.pcPlay;
    pc.addTransceiver("video", { direction: "recvonly" });
    pc.addTransceiver("audio", { direction: "recvonly" });

    pc.ontrack = (e) => {
      if (video.srcObject) return;
      console.log("ontrack");
      video.srcObject = e.streams[0];
      video.muted = true;
      video.playsInline = true;

      requestAnimationFrame(() => {
        video.play().catch(() => {});
      });
    };

    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);

    await new Promise((resolve) => {
      if (pc.iceGatheringState === "complete") return resolve();
      pc.onicegatheringstatechange = () => {
        if (pc.iceGatheringState === "complete") resolve();
      };
    });

    const res = await fetch("http://localhost:1985/rtc/v1/play/", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        api: "http://localhost:1985/rtc/v1/play/",
        streamurl: "webrtc://localhost/live/webcam",
        sdp: offer.sdp,
      }),
    });

    const data = await res.json();

    if (!data.sdp) {
      console.error("SRS error:", data);
      return;
    }

    await pc.setRemoteDescription({ type: "answer", sdp: data.sdp });
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
