// TuttoApricena - Source Protection
(function(){
  // Disable right-click context menu
  document.addEventListener('contextmenu', function(e){ e.preventDefault(); return false; });

  // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C, Ctrl+U
  document.addEventListener('keydown', function(e){
    // F12
    if(e.key === 'F12' || e.keyCode === 123){ e.preventDefault(); return false; }
    // Ctrl+Shift+I / Ctrl+Shift+J / Ctrl+Shift+C (DevTools)
    if(e.ctrlKey && e.shiftKey && (e.key==='I'||e.key==='i'||e.key==='J'||e.key==='j'||e.key==='C'||e.key==='c')){
      e.preventDefault(); return false;
    }
    // Ctrl+U (view source)
    if(e.ctrlKey && (e.key==='U'||e.key==='u')){ e.preventDefault(); return false; }
    // Ctrl+S (save page)
    if(e.ctrlKey && (e.key==='S'||e.key==='s')){ e.preventDefault(); return false; }
  });

  // Detect DevTools open via size difference (desktop)
  var threshold = 160;
  var devtoolsOpen = false;
  function checkDevTools(){
    var w = window.outerWidth - window.innerWidth > threshold;
    var h = window.outerHeight - window.innerHeight > threshold;
    if((w || h) && !devtoolsOpen){
      devtoolsOpen = true;
      // Redirect or clear page when devtools opened
      document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;background:#1a1a2e;color:#fff;text-align:center"><div><p style="font-size:1.5rem;font-weight:700">Accesso non consentito</p><p style="color:rgba(255,255,255,0.5);margin-top:8px">Gli strumenti per sviluppatori sono disabilitati su questo sito.</p><a href="/" style="display:inline-block;margin-top:20px;background:#e8a838;color:#1a1a2e;padding:10px 24px;border-radius:50px;font-weight:700;text-decoration:none">Torna alla home</a></div></div>';
    }
    if(!w && !h && devtoolsOpen){ devtoolsOpen = false; }
  }
  setInterval(checkDevTools, 1000);

  // Disable text selection on most elements
  document.addEventListener('selectstart', function(e){
    if(e.target.tagName==='INPUT'||e.target.tagName==='TEXTAREA') return true;
    e.preventDefault(); return false;
  });

  // Disable drag
  document.addEventListener('dragstart', function(e){ e.preventDefault(); return false; });

  // Clear console periodically
  var c = function(){};
  if(typeof console !== 'undefined'){
    try {
      var i = 0;
      Object.defineProperty(console, '_commandLineAPI', { get: function(){ throw new Error(); } });
    } catch(e){}
  }
})();
