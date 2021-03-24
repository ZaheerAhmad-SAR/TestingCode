<table>
    <thead>
         <tr>
                                <th>
                                    Site Code
                                </th>

                                <th>
                                    Device Category
                                </th>
                                <th>
                                    Device Manufacture
                                </th>
                                <th>
                                    Device Model
                                </th>
                                 <th>
                                    Device Sn
                                </th>
                                     
                                                                                                                                                      

                                                           
                               
                            </tr>
    </thead>
    <tbody>
        @foreach($devices as $device)
        
    
                                    <tr>
                                        <td>
                                           {{ $device->site_id }}
                                        </td>

                                        <td>
                                            {{ $device->device_categ }}
                                        </td>
                                        <td>
                                            {{ $device->device_manf }}
                                        </td>
                                        <td>
                                            {{ $device->device_model }}
                                        </td>
                                        <td>
                                            {{ $device->device_sn }}
                                        </td>                                                                                
                                        
                                                                              
                                    </tr>
        @endforeach
    </tbody>
</table>
