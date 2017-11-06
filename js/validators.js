function validateFormUsernameAcceptable () {
    let inputElem = document.getElementById("inputUsername");
    let r = /^[0-9a-zA-Z]{5,}$/ ;
    let isMatching = inputElem.value.match(r);

    console.log(isMatching);
    if (!isMatching &&  inputElem === document.activeElement) {
        inputElem.parentNode.className = "has-error";
        console.log("not matfcdhing");
    } else {
        inputElem.parentNode.className = "has-success";
    }
}