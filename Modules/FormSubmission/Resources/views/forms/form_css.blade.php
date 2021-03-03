<style>
.card-header{
cursor: pointer;
}
.date-cls{
font-size: 10px;
color: #1e3d73;
}
.disable_link {
pointer-events: none;
}
.adjudication-border{
border: #C30 1px dashed;
border-radius: 5px;
padding: 5px;
}
.validation-border{
border: #C30 1px solid;
border-radius: 5px;
padding: 5px;
}
.hr-line{
border-top: #808B96 1px dashed;
}
.wizard-dark .wizard .nav-tabs .nav-link.active {
border-bottom: 1px solid #1e3d73 !important;
}
/* Form Control */
.custom-control-input {
z-index: 1 !important;
}
.form-control-ocap {
display: block;
width: 100%;
height: calc(1.5em + .75rem + 2px);
padding: .375rem .75rem;
font-size: 1rem;
font-weight: 400;
line-height: 1.5;
color: #495057;
background-color: #fff;
background-clip: padding-box;
border: 1px solid #ced4da;
border-radius: .25rem;
transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
.form-control-ocap,
.form-control-ocap:focus,
.form-control-ocap:disabled,
.form-control-ocap[readonly] {
background: transparent;
border-color: var(--bordercolor);
font-size: 12px;
}
.form-group .form-control-ocap+.form-control-placeholder {
position: absolute;
top: 0;
padding: 7px 0 0 13px;
transition: all 200ms;
opacity: 0.5;
}
.form-group .form-control-ocap:focus+.form-control-placeholder {
transform: translate3d(0, -100%, 0);
opacity: 1;
}
.form-group .form-control-ocap.form-control-lg+.form-control-placeholder {
padding: 14px 0 0 13px;
}
.form-group .form-control-ocap.form-control-lg.float-input:focus {
padding: 1.2rem 1rem .3rem 1rem;
}
.form-group .form-control-ocap.form-control-lg.float-input:focus+.form-control-placeholder {
font-size: 70%;
transform: translate3d(0, -40%, 0);
}
.form-group .input-group .input-group-prepend+.form-control-ocap+.form-control-placeholder {
left: 40px;
}
.input-primary .form-control-ocap:focus {
border-color: var(--primarycolor);
}
.input-primary .form-control-ocap:focus+.form-control-placeholder {
color: var(--primarycolor);
}
.input-secondary .form-control-ocap:focus {
border-color: var(--secondary);
}
.input-secondary .form-control-ocap:focus+.form-control-placeholder {
color: var(--secondary);
}
.input-warning .form-control-ocap:focus {
border-color: var(--warning);
}
.input-warning .form-control-ocap:focus+.form-control-placeholder {
color: var(--warning);
}
.input-danger .form-control-ocap:focus {
border-color: var(--danger);
}
.input-danger .form-control-ocap:focus+.form-control-placeholder {
color: var(--danger);
}
.input-info .form-control-ocap:focus {
border-color: var(--info);
}
.input-info .form-control-ocap:focus+.form-control-placeholder {
color: var(--info);
}
.input-dark .form-control-ocap:focus {
border-color: var(--dark);
}
.input-dark .form-control-ocap:focus+.form-control-placeholder {
color: var(--dark);
}
.input-success .form-control-ocap:focus {
border-color: var(--success);
}
.input-success .form-control-ocap:focus+.form-control-placeholder {
color: var(--success);
}
.form-control-ocap,
.form-control-ocap:focus,
.btn-primary:not(:disabled):not(.disabled).active:focus,
.btn-primary:not(:disabled):not(.disabled):active:focus,
.show>.btn-primary.dropdown-toggle:focus,
.btn-secondary:not(:disabled):not(.disabled).active:focus,
.btn-secondary:not(:disabled):not(.disabled):active:focus,
.show>.btn-secondary.dropdown-toggle:focus,
.btn-primary.focus,
.btn-primary:focus {
outline: none;
box-shadow: none;
}
.h1,
h1 {
font-size: 1.5rem !important;
}
.h6,
h6 {
font-size: 0.7rem !important;
}
.collapse-body-bg {
background: #ffffff !important;
}
.error{
color: #C30;
}
.selected_form{
    background: lightgray;
    font-weight: 600;
    border: 1px solid #1E3D73;
}
</style>