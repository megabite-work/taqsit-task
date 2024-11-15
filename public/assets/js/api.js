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
  $(document).on("click", "#login_form_btn", async () => {
    let email = $('.tm-login-form input[name="email"]').val();
    let password = $('.tm-login-form input[name="password"]').val();

    if (!email && !password) {
      alert("All fields must be filled in.");
    }
    let body = { email, password };

    let errors = await post("/auth/login", body);
  });
});
