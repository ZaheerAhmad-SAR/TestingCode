<table>
    <thead>
         <tr>
                                <th>
                                    Site Code
                                </th>

                                <th>
                                    Site Name
                                </th>
                                <th>
                                    Address1
                                </th>
                                <th>
                                    Address2
                                </th>
                                 <th>
                                    City
                                </th>
                                <th>
                                    State
                                </th>      
                                <th>
                                    Country
                                </th> 
                                 <th>
                                    Zip Code
                                </th>                                                                                                                                                         

                                                           
                               
                            </tr>
    </thead>
    <tbody>
        @foreach($not_in_new_app_array as $study)
                                    <tr>
                                        <td>
                                           {{ $study->OIIRC_id}}
                                        </td>

                                        <td>
                                            {{ $study->site_title}}
                                        </td>
                                        <td>
                                            {{ $study->address_1}}
                                        </td>
                                        <td>
                                            {{ $study->address_2}}
                                        </td>
                                        <td>
                                            {{ $study->city}}
                                        </td>                                                                                
                                        <td>
                                            {{ $study->state}}
                                        </td> 
                                        <td>
                                            {{ $study->country}}
                                        </td> 
                                        <td>
                                            {{ $study->zip_code}}
                                        </td> 
                                                                              
                                    </tr>

                                @endforeach
    </tbody>
</table>
