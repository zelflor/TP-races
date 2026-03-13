<?php require '../private/components/navbar.php'; ?>
<div class="auth">

<h2>Register</h2>

<form id="registerForm">

<input 
type="text" 
name="username" 
placeholder="username"
pattern="[a-z0-9]+"
required
>

<input 
type="password" 
name="password" 
placeholder="password"
minlength="5"
required
>

<button type="submit">Register</button>

</form>


<h2>Login</h2>

<form id="loginForm">

<input 
type="text" 
name="username"
placeholder="username"
pattern="[a-z0-9]+"
required
>

<input 
type="password"
name="password"
placeholder="password"
minlength="5"
required
>

<button type="submit">Login</button>

</form>

<pre id="result"></pre>

</div>


    <script>

function sendAuth(form, action) {

    const data = new FormData(form)

    fetch("/api?action=" + action, {
        method: "POST",
        body: data
    })
    .then(res => res.json())
    .then(data => {

        document.getElementById("result").textContent =
            JSON.stringify(data, null, 2)

        if(data.success){
            console.log("connected")
        }

    })
}

document
.getElementById("registerForm")
.addEventListener("submit", e => {

    e.preventDefault()

    const username = e.target.username.value
    const password = e.target.password.value

    if(!/^[a-z0-9]+$/.test(username)){
        alert("username must be a-z 0-9")
        return
    }

    if(password.length < 5){
        alert("password min 5 characters")
        return
    }

    sendAuth(e.target, "register")

})


document
.getElementById("loginForm")
.addEventListener("submit", e => {

    e.preventDefault()

    sendAuth(e.target, "login")

})

</script>