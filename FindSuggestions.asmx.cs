using Microsoft.WindowsAzure;
using Microsoft.WindowsAzure.Storage;
using Microsoft.WindowsAzure.Storage.Blob;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Text.RegularExpressions;
using System.Web;
using System.Web.Hosting;
using System.Web.Script.Serialization;
using System.Web.Script.Services;
using System.Web.Services;

namespace WebRole1
{
    /// <summary>
    /// Summary description for FindSuggestions
    /// </summary>
    [WebService(Namespace = "http://tempuri.org/")]
    [WebServiceBinding(ConformsTo = WsiProfiles.BasicProfile1_1)]
    [System.ComponentModel.ToolboxItem(false)]
    // To allow this Web Service to be called from script, using ASP.NET AJAX, uncomment the following line. 
    [System.Web.Script.Services.ScriptService]
    public class FindSuggestions : System.Web.Services.WebService
    {
        static Trie myTrie;
        [WebMethod]
        public string DownloadData()
        {
            CloudStorageAccount storageAccount = CloudStorageAccount.Parse(ConfigurationManager.AppSettings["StorageConnectionString"]);
            CloudBlobClient blobClient = storageAccount.CreateCloudBlobClient();
            CloudBlobContainer container = blobClient.GetContainerReference("infoblob1");
            if (container.Exists())
            {
                foreach (IListBlobItem item in container.ListBlobs(null, false))
                {
                    if (item.GetType() == typeof(CloudBlockBlob))
                    {
                        CloudBlockBlob titleBlob = (CloudBlockBlob)item;
                        using (var fileStream = System.IO.File.OpenWrite(HostingEnvironment.ApplicationPhysicalPath + "\\titles.txt"))
                        {
                            titleBlob.DownloadToStream(fileStream);
                        }
                    }
                }
                return "Blobs downloaded.";
            }
            return "Container not found.";
        }
        
        [WebMethod]
        [ScriptMethod(ResponseFormat = ResponseFormat.Json)]
        public void buildTrie()
        {
            PerformanceCounter memProcess = new PerformanceCounter("Memory", "Available MBytes");
            myTrie = new Trie();

            StreamReader titleReader = new StreamReader(HostingEnvironment.ApplicationPhysicalPath + "\\titles.txt");
            int count = 0;
            string currLine = titleReader.ReadLine();
            while (currLine != null) 
            {
                count++;
                if (count % 10000 == 0)
                {
                    if (memProcess.NextValue() < 50)
                    {
                        break;
                    }
                }
                currLine = titleReader.ReadLine();
                if (Regex.IsMatch(currLine, @"^[a-zA-Z_]+$"))
                {
                    currLine = currLine.Replace('_', ' ');
                    myTrie.AddTitle(currLine);
                }
            }
        }

        [WebMethod]
        [ScriptMethod(ResponseFormat = ResponseFormat.Json)]
        public string searchPrefix(string prefix)
        {
            var outputSerializer = new JavaScriptSerializer();
            if (myTrie == null)
            {
                buildTrie();
            }
            string result = "";
            List<string> searchResults = myTrie.SearchForPrefix(prefix);
            foreach (string s in searchResults)
            {
                result = result + s + "%";
            }
            var jsonOutput = outputSerializer.Serialize(result);
            return jsonOutput;
        }
    }
}
