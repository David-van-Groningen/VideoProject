function initCarousel(id) {
  const root = document.getElementById(id);
  if (!root) return;
  const slides = root.querySelector('.slides');
  const total = slides.children.length;
  let idx = 0;

  function go(i) {
    idx = (i + total) % total;
    slides.style.transform = `translateX(${-idx*100}%)`;
  }

  root.querySelector('.carousel-next').addEventListener('click', ()=>go(idx+1));
  root.querySelector('.carousel-prev').addEventListener('click', ()=>go(idx-1));

  let auto = setInterval(()=>go(idx+1), 5000);
  root.addEventListener('mouseenter', ()=>clearInterval(auto));
  root.addEventListener('mouseleave', ()=>auto = setInterval(()=>go(idx+1), 5000));
}

const modal = document.getElementById('modal');
const modalForm = document.getElementById('modal-form');
const modalTitle = document.getElementById('modal-title');

function openModal(type='add', video=null){
  modal.classList.remove('hidden');
  modalTitle.textContent = type === 'add' ? 'Voeg video toe' : 'Bewerk video';
  modalForm.reset();
  modalForm.video_id.value = video?.id || '';
  modalForm.title.value = video?.title || '';
  modalForm.youtube_url.value = video?.youtube_url || '';
  modalForm.thumbnail_url.value = video?.thumbnail_url || '';
}

function closeModal(){
  modal.classList.add('hidden');
}

document.getElementById('modal-close').addEventListener('click', closeModal);

document.getElementById('btn-add-video').addEventListener('click', ()=>{
  openModal('add');
});

function attachVideoHandlers(){
  document.querySelectorAll('.play-btn').forEach(b=>{
    b.onclick = e=>{
      window.open(e.currentTarget.dataset.url,'_blank','noopener,noreferrer');
    }
  });

  document.querySelectorAll('.copy-btn').forEach(b=>{
    b.onclick = async e=>{
      const url = e.currentTarget.dataset.url;
      try { await navigator.clipboard.writeText(url); alert('Link gekopieerd!'); }
      catch(err){ prompt('Kopieer handmatig:', url); }
    }
  });

  document.querySelectorAll('.edit-btn').forEach(b=>{
    b.onclick = e=>{
      const block = e.currentTarget.closest('.video-block');
      const video = {
        id: block.dataset.id,
        title: block.querySelector('h4').textContent,
        youtube_url: block.querySelector('.play-btn').dataset.url,
        thumbnail_url: block.querySelector('.thumb').style.backgroundImage.replace(/url\(["']?|["']?\)/g,'')
      };
      openModal('edit', video);
    }
  });

  document.querySelectorAll('.delete-btn').forEach(b=>{
    b.onclick = async e=>{
      if(!confirm('Weet je zeker dat je deze video wilt verwijderen?')) return;
      const block = e.currentTarget.closest('.video-block');
      const videoId = block.dataset.id;

      const formData = new FormData();
      formData.append('video_id', videoId);

      const res = await fetch('ajax_delete_video.php', {method:'POST',body:formData});
      const json = await res.json();
      if(json.status==='success') block.remove();
      else alert(json.error || 'Fout bij verwijderen');
    }
  });
}

modalForm.addEventListener('submit', async e=>{
  e.preventDefault();
  const formData = new FormData(modalForm);
  const videoId = formData.get('video_id');
  const url = videoId ? 'ajax_edit_video.php' : 'ajax_add_video.php';
  
  const res = await fetch(url,{method:'POST',body:formData});
  const json = await res.json();
  
  if(json.status==='success'){
    location.reload();
  } else {
    alert(json.error || 'Fout bij opslaan');
  }
});

initCarousel('carousel');
attachVideoHandlers();
