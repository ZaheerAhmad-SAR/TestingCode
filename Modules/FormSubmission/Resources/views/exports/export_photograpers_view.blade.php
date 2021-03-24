<table>
    <thead>
         <tr>
                                <th>
                                    Site Code
                                </th>

                                <th>
                                    First Name
                                </th>
                                <th>
                                    Last Name
                                </th>
                                <th>
                                    Middle Name
                                </th>
                                 <th>
                                    Email
                                </th>
                                <th>
                                    Phone Number
                                </th>      
                                                                                                                                                      

                                                           
                               
                            </tr>
    </thead>
    <tbody>
        @foreach($phdata as $key => $pdata)
        
    
                                    <tr>
                                        <td>
                                           {{ $pdata['sitecode']}}
                                        </td>

                                        <td>
                                            {{ $pdata['first_name']}}
                                        </td>
                                        <td>
                                            {{ $pdata['last_name']}}
                                        </td>
                                        <td>
                                            {{ $pdata['middle_name']}}
                                        </td>
                                        <td>
                                            {{ $pdata['email']}}
                                        </td>                                                                                
                                        <td>
                                            {{ $pdata['phone_number']}}
                                        </td>  
                                                                              
                                    </tr>
        @endforeach
    </tbody>
</table>
