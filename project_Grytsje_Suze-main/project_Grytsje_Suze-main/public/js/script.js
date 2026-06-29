window.addEventListener('load', function () {
    const track = document.getElementById('catalogueTrack');
    const original = Array.from(track.children);

    // Clone 3 extra sets so it never runs dry on any screen
    for (let i = 0; i < 3; i++) {
        original.forEach(function (item) {
            track.appendChild(item.cloneNode(true));
        });
    }

    const singleSetWidth = original.reduce(function (sum, el) {
        const style = getComputedStyle(el);
        return sum + el.offsetWidth + parseFloat(style.marginLeft) + parseFloat(style.marginRight);
    }, 0);

    let position = 0;

    function animate() {
        position -= 0.4;
        if (Math.abs(position) >= singleSetWidth) {
            position += singleSetWidth;
        }
        track.style.transform = 'translateX(' + position + 'px)';
        requestAnimationFrame(animate);
    }
    animate();
});
