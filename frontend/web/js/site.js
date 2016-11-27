
function changeCaption(){
    button = $('#showhidebutton');
    current = button.text();
    if (current == 'Show'){
        button.text("Hide");
    }else{
        button.text("Show");
    }
}