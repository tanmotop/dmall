var myScroll;

function loaded () {
    myScroll = new IScroll('#wrapper', { eventPassthrough: true, scrollX: true, scrollY: false, preventDefault: false });
}
