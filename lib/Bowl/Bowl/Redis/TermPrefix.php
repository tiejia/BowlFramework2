<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lx
 * Date: 11-9-6
 * Time: 下午12:38
 * To change this template use File | Settings | File Templates.
 */

class Bowl_Redis_TermPrefix
{

    // TermInfo
    const TerminalIdToGroupId = "TerminalIdToGroupId";
    const TerminalsByGroupId = "TerminalsByGroupId";

    const TermianlIdExist = "TermianlIdExist";

    // RegisterInfo
    const TerminalIdToRegisterData = "TerminalIdToRegisterData";
    const TermSession = "TermSession";
    const PasswdtoFactoryId = "PasswdtoFactoryId";
    const AuthFailedTimes = "AuthFailedTimes";

    // TaskInfo
    const TaskFlag = "TaskFlag";
    const ResourceXMLPath = "ResourceXMLPath";
    const ResourceXMLPathMD5 = "ResourceXMLPathMD5";
    const ResourceXMLPathSize = "ResourceXMLPathSize";

    // SelectTime
    const TerminalSelectTime = "TerminalSelectTime";

    //cpu,memory,disk information
    const TerminalCpu = "TerminalCpu";
    const TerminalMem = "TerminalMem";
    const TerminalDisk = "TerminalDisk";

    const curtask = "curtask";
    const rusage = "rusage";
    const filelist = "filelist";
    const command = "command";
    const command_resp = "command_resp";
    
    const sendsms = "SEND:SMS";
    const phonesmscode = "PhoneSMSCode";

}


?>