function likePost(postId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../back-end/like.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const likeCount = document.getElementById("like-count-" + postId);
            if (likeCount) {
                likeCount.textContent = "❤️ " + xhr.responseText + " likes";
            }
        }
    };
    xhr.send("post_id=" + postId);
}

function submitComment(postId, userProfile, userName) {
    const input = document.getElementById("comment-input-" + postId);
    const comment = input.value.trim();
    if (comment === "") return;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../back-end/comment.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200 && xhr.responseText === "success") {
            const commentsDiv = document.getElementById("comments-" + postId);
            const newComment = document.createElement("div");
            newComment.className = "comment-item";
            newComment.innerHTML =
                "<img src='../assets/uploads/" + userProfile + "' class='circle-thumb-small' alt=''>" +
                "<div class='comment-text'><strong>" + userName + "</strong><span>" + comment + "</span></div>";
            commentsDiv.appendChild(newComment);
            input.value = "";
            document.getElementById("submit-button-" + postId).disabled = true;
        }
    };
    xhr.send("post_id=" + postId + "&comment=" + encodeURIComponent(comment));
}

document.addEventListener("DOMContentLoaded", function () {
    const avatarIcon = document.getElementById('avatarIcon');
    const dropdown = document.getElementById('dropdownMenu');
    if (avatarIcon && dropdown) {
        avatarIcon.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });
        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    }

    document.querySelectorAll('.comment-form').forEach(form => {
        const input = form.querySelector('.comment-input');
        const button = form.querySelector('.comment-button');
        input.addEventListener('input', function () {
            button.disabled = input.value.trim() === '';
        });
    });

    document.querySelectorAll('.logout-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('Yakin ingin logout?')) {
                window.location.href = '../front-end/logout.php';
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const area = document.getElementById('messageArea');
    if (area) {
        area.scrollTop = area.scrollHeight;
    }
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-like]").forEach(btn => {
        btn.addEventListener("click", () => {
            likePost(btn.dataset.like);
        });
    });

    document.querySelectorAll("[data-confirm]").forEach(link => {
        link.addEventListener("click", e => {
            if (!confirm(link.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".like-button").forEach(btn => {
        btn.addEventListener("click", () => {
            const postId = btn.dataset.postId;
            likePost(postId);
        });
    });

    document.querySelectorAll(".confirm-delete").forEach(link => {
        link.addEventListener("click", (e) => {
            const confirmText = link.dataset.confirmText || "Yakin?";
            if (!confirm(confirmText)) {
                e.preventDefault();
            }
        });
    });
});

function likePost(postId) {
    fetch("../back-end/like.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `post_id=${postId}`
    })
    .then(response => response.text())
    .then(result => {
        document.getElementById(`like-count-${postId}`).innerText = `❤️ ${result} likes`;
    });
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".confirm-delete").forEach(link => {
        link.addEventListener("click", (e) => {
            const confirmText = link.dataset.confirmText || "Yakin?";
            if (!confirm(confirmText)) {
                e.preventDefault();
            }
        });
    });
});

function toggleTambah() {
    document.getElementById("formTambah").style.display = "block";
    document.getElementById("formInput").style.display = "none";
}
function showInputForm() {
    const role = document.getElementById("selectRole").value;
    if (role) {
        document.getElementById("formInput").style.display = "block";
        document.getElementById("roleHidden").value = role;
    } else {
        document.getElementById("formInput").style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', () => {
  const area = document.getElementById('messageArea');
  if (area) {
    area.scrollTop = area.scrollHeight;
  }
});

function toggleForm() {
    var form = document.getElementById("formIdolBaru");
    form.style.display = form.style.display === "none" ? "block" : "none";
}

document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
    });
});