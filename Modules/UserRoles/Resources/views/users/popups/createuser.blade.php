<div class="modal fade" tabindex="-1" role="dialog" id="createUser">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <p class="modal-title">Add User</p>
            </div>
            <form action="{{route('users.store')}}" enctype="multipart/form-data" method="POST" class="user-store-form" id="user-store-form-5">
                @include('userroles::users.popups.userform', ['roles'=>$roles, 'assigned_roles'=>[], 'errors'=>$errors ])
            </form>
        </div>
    </div>
</div>
