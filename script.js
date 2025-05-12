document.getElementById("picnicForm").addEventListener("submit", function (e) {
  const name = document.getElementById("name").value.trim();
  const age = parseInt(document.getElementById("age").value);
  const email = document.getElementById("email").value.trim();
  const mobile = document.getElementById("mobile").value.trim();
  const gender = document.getElementById("gender").value.trim().toLowerCase();
  const desc = document.getElementById("desc").value.trim();

  const genderOptions = ["male", "female", "other"];

  // Name
  if (name === "") {
    alert("Please enter your full name.");
    e.preventDefault();
    return;
  }

  // Age
  if (isNaN(age) || age < 1 || age > 120) {
    alert("Please enter a valid age between 1 and 120.");
    e.preventDefault();
    return;
  }

  // Email
  const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
  if (!emailPattern.test(email)) {
    alert("Please enter a valid email address.");
    e.preventDefault();
    return;
  }

  // Mobile
  if (!/^\d{10}$/.test(mobile)) {
    alert("Mobile number must be exactly 10 digits.");
    e.preventDefault();
    return;
  }

  // Gender
  if (!genderOptions.includes(gender)) {
    alert("Please enter gender as Male, Female, or Other.");
    e.preventDefault();
    return;
  }

  // Description
  if (desc.length < 5) {
    alert("Please provide a suggestion (at least 5 characters).");
    e.preventDefault();
    return;
  }

  // All good
  alert("Form looks good! Submitting...");
});
