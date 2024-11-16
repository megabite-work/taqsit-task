const request = async (url, method, body = null) => {
  const headers = new Headers();
  headers.append("Content-Type", "application/json");
  headers.append("Accept", "application/json");
  headers.append("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr("content"));
  body = JSON.stringify(body);
  try {
    const response = await fetch(url, {
      method,
      body,
      headers,
    });

    return await response.json();
  } catch (error) {
    console.log(error);
  }
};

const post = async (url, body = null) => {
  return await request(url, "POST", body);
};

const patch = async (url, body = null) => {
  return await request(url, "PATCH", body);
};

const put = async (url, body = null) => {
  return await request(url, "PUT", body);
};

const get = async (url, body = null) => {
  return await request(url, "GET", body);
};

const del = async (url, body = null) => {
  return await request(url, "DELETE", body);
};

$(document).ready(async function () {
  $("#post_image").on("change", function (event) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#post_image_preview").removeClass('d-none');
      $("#post_image_preview").addClass('d-block');
      $("#post_image_preview img").attr("src", e.target.result);
    };
    reader.readAsDataURL(event.target.files[0]);
  });
});
