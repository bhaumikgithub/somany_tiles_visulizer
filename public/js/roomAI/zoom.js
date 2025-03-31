(function(){
    const img = document.getElementById('roomCanvas');
    let mouseX;
    let mouseY;
    let mouseTX;
    let mouseTY;
    let startX = 0;
    let startY = 0;
    let panning = false;

    const ts = {
      scale: 1,
      rotate: 0,
      translate: {
        x: 0,
        y: 0
      }
    };

    img.onwheel = function(event) {
      event.preventDefault();
      //need more handling  to avoid fast scrolls
      var func = img.onwheel;
      img.onwheel = null;

      const prevScale = ts.scale;

      let rec = img.getBoundingClientRect();
      let x = (event.clientX - rec.x) / ts.scale;
      let y = (event.clientY - rec.y) / ts.scale;

      let delta = (event.wheelDelta ? event.wheelDelta : -event.deltaY);
      ts.scale = (delta > 0) ? (ts.scale + 0.2) : (ts.scale - 0.2);
      ts.scale = Math.max(ts.scale, 1)

      if(prevScale === ts.scale) {
        img.onwheel = func;
        return
      }

      //let m = (ts.scale - 1) / 2;
      let m = (delta > 0) ? 0.1 : -0.1;
      ts.translate.x += (-x * m * 2) + (img.offsetWidth * m);
      ts.translate.y += (-y * m * 2) + (img.offsetHeight * m);

      setTransform();
      img.onwheel = func;
    };

    img.onmousedown = function(event) {
      event.preventDefault();
      panning = true;
      img.style.cursor = 'grabbing';
      mouseX = event.clientX;
      mouseY = event.clientY;
      mouseTX = ts.translate.x;
      mouseTY = ts.translate.y;
    };

    img.onmouseup = function(event) {
      panning = false;
      img.style.cursor = 'grab';
    };

    img.onmousemove = function(event) {
      event.preventDefault();
    //   let rec = img.getBoundingClientRect();
    //   let xx = event.clientX - rec.x;
    //   let xy = event.clientY - rec.y;

      const x = event.clientX;
      const y = event.clientY;
      pointX = (x - startX);
      pointY = (y - startY);
      if (!panning) {
        return;
      }
      ts.translate.x =
        mouseTX + (x - mouseX);
      ts.translate.y =
        mouseTY + (y - mouseY);
      setTransform();
    };

    function setTransform() {
      const steps = `translate(${ts.translate.x}px,${ts.translate.y}px) scale(${ts.scale}) translate3d(0,0,0)`;
      img.style.transform = steps;
    }

    function reset() {
      ts.scale = 1;
      ts.translate = {
        x: 0,
        y: 0
      };
      rotate.value = 180;
      img.style.transform = 'none';
    }

    setTransform();    
})();