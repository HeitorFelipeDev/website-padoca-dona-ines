import { showPassword } from "../utils/showPassword.js";
showPassword();

const buttonSubmitLogin = document.getElementById("button-submit-login");
buttonSubmitLogin.addEventListener("click", submitForm);

async function submitForm(e) {
  e.preventDefault();

  const data = {
    id: Number(document.getElementById("id").value),
    password: document.getElementById("password").value
  }

  checkResponse(await fetchLogin(data));
}

async function fetchLogin(data) {
  const url = "controllers/administradorController.php";
  data.operation = "login";

  console.log(data)

  const req = await fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
    },
    body: JSON.stringify(data)
  });

  const result = await req.json();
  console.log(result)
  return result;
}

async function checkResponse(result) {
  if (result.status === "error") {
    document.querySelectorAll(".message-error").forEach(message => message.remove());
    document.querySelectorAll(".input-container").forEach(input => input.classList.remove("border"));

    if (result.invalid_fields) {
      result.invalid_fields.forEach((field) => {
        let inputContainer = document.getElementById(field.field).parentElement;
        let messageError = document.createElement("span");

        messageError.textContent = field.error;
        messageError.classList.add("message-error");
        messageError.classList.add("text-[12px]");
        messageError.classList.add("mt-1");
        messageError.classList.add("text-red-500");

        inputContainer.classList.add("border");
        inputContainer.classList.add("border-red-500");

        inputContainer.insertAdjacentElement("afterend", messageError);
      })

      return;
    }

    let lastInput = document.querySelector(".input-container:last-of-type");
    let messageError = document.createElement("span");

    messageError.textContent = result.message;
    messageError.classList.add("message-error");
    messageError.classList.add("mt-1");
    messageError.classList.add("text-[12px]");
    messageError.classList.add("text-red-500");

    lastInput.insertAdjacentElement("afterend", messageError);
  }
}